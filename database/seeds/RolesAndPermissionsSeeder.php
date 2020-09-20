<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        Permission::create(['name' => 'user-activity','removable'=> 0]);
        Permission::create(['name' => 'manage-user','removable'=> 0]);
        Permission::create(['name' => 'manage-permission','removable'=> 0]);
        Permission::create(['name' => 'manage-role','removable'=> 0]);
        Permission::create(['name' => 'manage-setting','removable'=> 0]);
        Permission::create(['name' => 'manage-payment-gateway','removable'=> 0]);

        $role = Role::create(['name' => 'admin','removable'=> 0]);
        $role->givePermissionTo(Permission::all());
        Role::create(['name' => 'users','removable'=> 0]);
    }
}
