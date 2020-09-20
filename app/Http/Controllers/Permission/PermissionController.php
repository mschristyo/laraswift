<?php

namespace App\Http\Controllers\Permission;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use UxWeb\SweetAlert\SweetAlert;
use Auth;

class PermissionController extends Controller
{
    public function __construct()
    {
        if (setting('email_verification')) {
            $this->middleware(['verified']);
        }
        $this->middleware(['auth','web','permission:manage-permission','2fa']);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $permissions = Permission::all();
        return view('permission.index')->with('permissions', $permissions);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('permission.create');
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
            'name' => 'required|regex:/^[a-zA-Z0-9\-_\.]+$/|min:4|unique:permissions',
        ],[
          'regex' => 'Invalid Entry! Only letters,underscores,hypens and numbers are allowed',
        ]);

        $this->permission =  Permission::create([
            'name' => str_replace(' ', '-', strtolower($request->name)),
        ]);

        // Logging activity for created role
        activity()->performedOn($this->permission)->withProperties(['name'=>$this->permission->name,'by'=>Auth::user()->username])->causedBy(Auth::user())->log('Permission was created');

        return redirect()->back()->with('success', 'Permission Created Successfully');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->permission = Permission::findOrfail($id);
        return view('permission.edit')->with('permission', $this->permission);
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

        $permission = Permission::find($id);


        $this->validate($request, [
            'name' => 'required|regex:/^[a-zA-Z0-9\-_\.]+$/|min:4|unique:permissions,name,'.$permission->id,
        ],[
          'regex' => 'Invalid Entry! Only letters,underscores,hypens and numbers are allowed',
        ]);

        $permission->name = str_replace(' ', '-', strtolower($request->name));

        // Logging activity for created role
        activity()->performedOn($permission)->withProperties(['name'=>$permission->name,'by'=>Auth::user()->username])->causedBy(Auth::user())->log('Permission was updated');

        $permission->save();

        return redirect()->back()->with('success', 'Permission Updated Sucessfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->permission = Permission::find($id);

        //default permission cannot be deleted
        if (!$this->permission->removable) {
            alert()->error('This permission cannot be deleted')->persistent('Close');
            return redirect()->back();
        }

        $this->permission->delete();
        // Logging activity for created role
        activity()->performedOn($this->permission)->withProperties(['name'=>$this->permission->name,'by'=>Auth::user()->username])->causedBy(Auth::user())->log('Permission was deleted');

        return redirect()->back()->with('success', 'Permission Deleted Successfully');
    }
}
