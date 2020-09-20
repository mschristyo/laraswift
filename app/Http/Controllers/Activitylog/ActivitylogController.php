<?php

namespace App\Http\Controllers\Activitylog;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;
use App\User;
use Auth;

class ActivitylogController extends Controller
{
    public function __construct()
    {
        if (setting('email_verification')) {
            $this->middleware(['verified']);
        }

        $this->middleware(['auth','web','2fa']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if ($this->isAdmin()) {
            return $this->adminLog();
        }
        return $this->userLog();
    }

    /**
     * If user has admin role it fetches all logs
     * @return [type] [description]
     */
    private function adminLog()
    {
        $activities = Activity::orderByDesc('created_at')->get();
        return view('activitylog.adminLog', [
        'activities' => $activities,
      ]);
    }

    /**
     * Authenthicated user logs only
     * @return [type] [description]
     */
    private function userLog()
    {
        $activities = auth()->user()->actions->sortByDesc('created_at');
        return view('activitylog.userLog', [
            'activities' => $activities,
        ]);
    }

    /**
     * Check if Authenticated user is admin
     * @return boolean [description]
     */
    private function isAdmin()
    {
        return (auth()->user()->roles->first()['name'] == 'admin')? true : false;
    }
}
