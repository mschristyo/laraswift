<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\User;
use App\Role;
use App\Permission;
use App\Charts\Charts;
use Carbon\Carbon;
use Spatie\Activitylog\Models\Activity;
use App\Subscriptions;
use App\Stripe\StripeCustomer;
use \Stripe\Exception\AuthenticationException;
use \Stripe\Exception\ApiConnectionException;
use \Stripe\Exception\ApiErrorException;
use \Stripe\Exception\RateLimitException;
use \Stripe\Exception\InvalidRequestException;

class DashboardController extends Controller
{
    private $user;
    private $chart;
    private $subscriptions;
    private $stripeCustomer;


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Charts $chart, User $user, Subscriptions $subscriptions, StripeCustomer $stripeCustomer)
    {
        if (setting('email_verification')) {
            $this->middleware(['verified']);
        }
        $this->middleware(['auth','web','2fa']);

        $this->user = $user;

        $this->chart = $chart;
        $this->subscriptions = $subscriptions;
        $this->stripeCustomer = $stripeCustomer;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if ($this->isAdmin()) {
            return $this->adminDashboard();
        }

        return $this->defaultDashboard();
    }

    /**
     * return admin users to admin dashboard
     * @return [type] [description]
     */
    private function adminDashboard()
    {
        $users = User::count();
        $roles = Role::count();
        $chart = $this->userCount();
        $latest_user = $this->getLatestUsersCount();
        $latestSubscription = collect([]);
        $total_income = $this->totalIncome();

        try {
            $latestSubscription =  $this->getLatestSubscription();
        } catch (ApiConnectionException $th) {
            $error_msg = $th->getMessage();
        } catch (AuthenticationException $th) {
            $error_msg = $th->getMessage();
        } catch (InvalidRequestException $th) {
            $error_msg = $th->getMessage();
        }

        return view('dashboard.admin', [
            'latestUser'=> $this->chart,
            'users' => $users,
            'roles'=> $roles,
            'latest_users' => $latest_user,
            'latestSubscription'=> $latestSubscription,
            'total_income' => $total_income
          ]);
    }

    private function userCount()
    {
        $data = collect([]); // Could also be an array
      $label = collect([]); // Could also be an array

      for ($days_backwards = 4; $days_backwards >= 0; $days_backwards--) {
          $next_month = ($days_backwards !== 0)? $days_backwards - 1: 0;
          $data->push(User::whereBetween('created_at', [today()->subMonths($days_backwards)->startOfMonth(),today()->subMonths($days_backwards)->endOfMonth()])->count());
          $label->push(today()->subMonths($days_backwards)->format('M-y'));
      }

        $this->chart->labels($label);
        $this->chart->dataset('Registration History', 'bar', $data)
      ->backgroundColor('rgba(108, 178, 235, 0.9)');
        $this->chart->displayLegend(false);
        $this->chart->barWidth(0.4);
        return $this->chart;
    }

    /**
     * Function directs users to default dashboard
     * @return view default user dashboard
     */
    private function defaultDashboard()
    {
        return view('dashboard.default');
    }

    /**
     * Check if Authenticated user is admin
     * @return boolean [description]
     */
    private function isAdmin()
    {
        return (auth()->user()->roles->first()['name'] == 'admin')? true : false;
    }

    private function getFullname($id)
    {
        $user = $this->user->find($id);
        return ($user)? $user->fullname: null;
    }

    private function getLatestSubscription()
    {
        $subscriptions = collect([]);
        $values = $this->subscriptions->take(8)->orderByDesc('id')->get();
        foreach ($values as $val) {
            if ($this->stripeCustomer->getSubscription($val->subscription_id)) {
                $subscriptions->push([
            'id' => $val->id,
            'fullname'=>$this->getFullname($val->user_id),
            'stripe_id'=>$val->subscription_id,
            'plan_name'  => $val->plan_name,
            'amount' => $val->subscription_amount,
          ]);
            }
        }
        return collect($subscriptions);
    }

    private function getLatestUsersCount()
    {
        return $this->user->whereBetween('created_at', [
        now()->subDay(3)->toDateString(),now()->addDay()->toDateString()
      ])->count();
    }

    public function totalIncome()
    {
        // Overrall Amount Accumulated
        $overrall_income = collect($this->subscriptions->all())->map(function ($value) {
            return $value->subscription_amount;
        })->sum();

        return $overrall_income;
    }
}
