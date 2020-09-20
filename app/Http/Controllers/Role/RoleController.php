<?php

namespace App\Http\Controllers\Role;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use UxWeb\SweetAlert\SweetAlert;
use Auth;

class RoleController extends Controller
{
    private $role;

    public function __construct()
    {
        if (setting('email_verification')) {
            $this->middleware(['verified']);
        }
        $this->middleware(['permission:manage-role','web','auth','2fa']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Role::all();
        return view('roles.index')->with('roles', $roles);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('roles.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $this->validate($request, [
            'name' => 'required|min:4|max:20|regex:/^[a-zA-Z0-9\-_\.]+$/',
        ],[
          'regex' => 'Invalid Entry! Only letters,underscores,hypens and numbers are allowed',
        ]);

        $this->role =  Role::create([
            'name' => str_replace(' ', '-', strtolower($request->name))
        ]);

        $this->role->save();

        // Logging activity for created role
        activity()
                ->performedOn($this->role)
                ->withProperties(['name'=>$this->role->name,'by'=>Auth::user()->username])
                ->causedBy(Auth::user())
                ->log('Role was created');

        return redirect()->back()->with('success', 'Role Created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $role = Role::find($id);
        $permissions = $role->permissions()->get();
        $allpermissions = Permission::all();
        return view('roles.show')
                    ->with('role', $role)
                    ->with('permissions', $permissions)
                    ->with('allpermissions', $allpermissions);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $role = Role::find($id);
        return view('roles.edit')->with('role', $role);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $this->role = Role::find($id);

        $this->validate($request, [
            'name' => 'required|regex:/^[a-zA-Z0-9\-_\.]+$/|min:3|max:20|unique:roles,name,'.$this->role->id
        ],[
          'regex' => 'Invalid Entry! Only letters,underscores,hypens and numbers are allowed',
        ]);

        $this->role->name = str_replace(' ', '-', strtolower($request->name));
        $this->role->save();

        // Logging activity for created role
        activity()
                ->performedOn($this->role)
                ->withProperties(['name'=>$this->role->name,'by'=>Auth::user()->username])
                ->causedBy(Auth::user())
                ->log('Role was updated');

        return redirect()->back()->with('success', 'Role Updated Sucessfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $this->role = Role::find($id);

        //Default role cannot be deleted
        if (!$this->role->removable) {
            alert()->error('This role cannot be deleted')->persistent('Close');
            return redirect()->back();
        }

        // Logging activity for created role
        activity()
                    ->performedOn($this->role)
                    ->withProperties(['name'=>$this->role->name,'by'=>Auth::user()->username])
                    ->causedBy(Auth::user())
                    ->log('Role was deleted');

        $this->role->delete();
        return redirect()->back()->with('success', 'Role Deleted Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function assignPermission(Request $request, $id)
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $role = Role::find($id);
        $permissions = $role->permissions()->get();
        $role->revokePermissionTo($permissions);

        // Logging activity for created role
        activity()
                    ->performedOn($role)
                    ->withProperties(['name'=>$role->name,'by'=>Auth::user()->username])
                    ->causedBy(Auth::user())
                    ->log('Permission assigned to Role was');

        $role->givePermissionTo($request->permissions);
        return redirect()->back()->with('success', 'Permissions assigned to Role successfully');
    }
}
