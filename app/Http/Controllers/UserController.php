<?php

namespace App\Http\Controllers;

use Hash;
use Auth;
use App\User;
use App\Role;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use URL;
use UxWeb\SweetAlert\SweetAlert;
use PragmaRX\Countries\Package\Countries;

class UserController extends Controller
{
    protected $user;
    protected $role;
    protected $countries;
    protected $activity;

    public function __construct(User $user, Role $role, Countries $country, Activity $activity)
    {
        if (setting('email_verification')) {
            $this->middleware(['verified']);
        }

        $this->middleware(['auth','web','permission:manage-user','2fa']);
        $this->user = $user;
        $this->role = $role;
        $this->countries = $country->all()->sortBy('name.common')->pluck('name.common');
        $this->activity = $activity;
    }

    /**
     * View all users
     * @return Model User model
     */
    public function index()
    {
        $users = $this->fetchUsers(15, request()->input('search'));

        return view("users.admin.index", ['users'=>$users]);
    }

    /**
     * Create user form
     * @return string
     */
    public function create()
    {
        $roles = $this->role->all();
        $counties = $this->countries;
        return view("users.admin.create", [
              'roles' => $roles,
              'countries' => $counties,
            ]);
    }

    /**
     * Save user details
     * @param  Request $request
     * @return string
     */
    public function store(Request $request)
    {
        $this->validate($request, [
                'fullname' => 'required|max:255|regex:(^([a-zA-Z_ ]+)([a-zA-Z]+)([a-zA-Z]+))',
                'email' => 'required|string|email|max:255|unique:users',
                'username' => 'required|string|max:50|unique:users|regex:([a-zA-Z0-9_@]+)|',
                'password' => 'required|string|min:8|confirmed',
                'password_confirmation' => 'required|string|min:8|same:password',
                'birthday' => 'required|date|date_format:Y-m-d',
                'phone' => 'required|numeric',
                'address' => 'required|regex:/^[A-Za-z0-9_.,() ]+$/|max:255',
                'country' => 'required|string|max:255',
            ],[
              'fullname.regex' => 'Invalid Entry! The fullname only letter and numbers are allowed',
              'address.regex' => 'Invalid Entry! The address only letter, underscore,hypens and numbers are allowed',
            ]);

        $user = $this->user->create([
                'fullname' => $request->fullname,
                'username' => $request->username,
                'email' => $request->email,
                'password'=> bcrypt($request->password),
                'birthday'=> $request->birthday,
                'phone'=> $request->phone,
                'address' =>$request->address,
                'country' =>$request->country,
                'avatar' => URL::to('/')."/uploads/avatar/avatar.png",
            ]);

        if ($user) {
            $user->assignRole($request->role);
            // Logging activity for created role
            activity()->performedOn($user)->withProperties(['name'=>($request->username)?$request->username:$request->email,'by'=>Auth::user()->username])->causedBy(Auth::user())->log('User was created');
            return redirect()->back()->with('success', 'User Created Successfully');
        } else {
            return redirect()->back()->with('error', 'An error occured check form!');
        }
    }

    /**
     * Edit user
     * @param  string $id User id
     * @return string
     */
    public function edit($id)
    {
        $user = $this->user->find($id);
        $roles = $this->role->all();
        $user_role = $user->roles->first();
        $activities = $this->activity->whereCauserId($id)->orderByDesc('created_at')->take(10)->get();
        return view('users.admin.edit', [
              'user' => $user,
              'roles' => $roles,
              'user_role' => $user_role,
              'countries' => $this->countries,
              'activities' => $activities,
            ]);
    }

    /**
     * Update user's details
     * @param  Request $request
     * @param  string  $id      User id
     * @return string
     */
    public function update(Request $request, $id)
    {
        $user = $this->user->find($id);
        $this->validate($request, [
                  'fullname' => 'nullable|max:255|regex:regex:/^[A-Za-z0-9_.,() ]+$/',
                  'phone' => 'nullable|string|numeric',
                  'birthday' => 'nullable|date|date_format:Y-m-d',
                  'address' => 'nullable|regex:/^[A-Za-z0-9_.,() ]+$/|max:255',
                  'country' => 'nullable|string',
                  'role' => 'required|string',
                  'status' => 'required|string',
              ],[
                'fullname.regex' => 'Invalid Entry! The fullname only letter and numbers are allowed',
                'address.regex' => 'Invalid Entry! The address only letter, underscore,hypens and numbers are allowed',
              ]);

        // Logging activity for created role
        $user->fullname = $request->fullname;
        $user->phone= $request->phone;
        $user->birthday= $request->birthday;
        $user->country= $request->country;
        $user->address= $request->address;
        $user->status= $request->status;
        $user->save();

        // Re-assigining role
        $this->reassignRole($id, $request->role);


        activity()->performedOn($user)->withProperties(['name'=>$user->username,'by'=>Auth::user()->username])->causedBy(Auth::user())->log('User was updated');
        return redirect()->back()->with('success', 'User Updated Successfully');
    }

    /**
     * Delete user
     * @param  string $id user id
     * @return string     [description]
     */
    public function destroy($id)
    {
        $this->user = $this->user->find($id);

        if ($this->isAdmin($this->user->username)) {
            alert()->error('Admin User cannot be deleted')->persistent('Close');
            return redirect()->back();
        }
        // Logging Activity for created user
        activity()->performedOn($this->user)->withProperties(['name'=>$this->user->username,'by'=>Auth::user()->username])->causedBy(Auth::user())->log('User was deleted');
        $this->user->delete();
        return redirect()->back()->with('success', 'User account deleted');
    }

    private function fetchUsers($pagination, $search = null)
    {
        $query = $this->user->query();

        if ($search) {
            $query->where(function ($value) use ($search) {
                $value->where('fullname', 'like', "%{$search}%");
                $value->orWhere('username', 'like', "%{$search}%");
                $value->orWhere('email', 'like', "%{$search}%");
            });
        }

        $query_output = $query->orderByDesc('id')->paginate($pagination);

        if ($search) {
            $query_output->appends(['search' => $search]);
        }

        return $query_output;
    }

    /**
     * Check for user type if admin or not
     * @param  string $username User username
     * @return boolean           true|false
     */
    public function isAdmin($username)
    {
        if ($username == 'admin' || $username == 'Admin') {
            return true;
        }
        return false;
    }


    /**
     * Function remove old role and assign new role to user
     * @param  string $id       user id
     * @param  string $new_role new role to be assigned
     * @return boolean           true|false
     */
    private function reassignRole($id, $new_role)
    {
        $user = $this->user->find($id);
        // Get user's previous role
        $role = $user->roles->first();

        // Remove role previously assigned to user if exist
        if ($role) {
            $user->removeRole($role->name);
        }
        // Assign new role to user
        $state = $user->assignRole($new_role);

        if ($state) {
            return true;
        } else {
            return false;
        }
    }


    /**
     * Updating login details
     * @param  Request $request
     * @param  string  $id      user id
     * @return string
     */
    public function userLogin(Request $request, $id)
    {
        $user = $this->user->find($id);

        $this->validate($request, [
                  'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
                  'username' => 'required|string|max:50|regex:([a-zA-Z0-9_@]+)|unique:users,username,'.$user->id,
                  'password' => 'nullable|min:8|string|confirmed',
                  'password_confirmation' => 'nullable|same:password',
              ]);

        // Logging activity for created role
        activity()->performedOn($user)->withProperties(['name'=>$user->username,'by'=>Auth::user()->username])->causedBy(Auth::user())->log('User login details updated');
        $user->email = $request->email;
        $user->username = $request->username;
        if (!is_null($request->password)) {
            $user->password = bcrypt($request->password);
        }

        $user->save();
        return redirect()->back()->with('success', 'User Updated Successfully');
    }

    public function userActivityLog($id)
    {
        $user = $this->user->find($id);
        $activities = $this->activity->whereCauserId($id)->orderByDesc('created_at')->get();
        return view('users.admin.userLog', [
            'user' => $user,
            'activities' => $activities,
          ]);
    }
}
