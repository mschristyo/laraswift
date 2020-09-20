<?php

namespace Tests\Feature\Controller;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;
use Faker\Generator as Faker;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Str;


class PermissionControllerTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A test to redirect un-authenticated user to login page.
     * @test
     * @return void
     */
    public function authentication_of_user_to_access_permissions_list()
    {
      $this->get('/permission')
         ->assertRedirect('/login');
    }

    /**
     * A test to restrict users without permission to access permission list.
     * @test
     * @return void
     */
    public function restrict_regular_user_without_permission_access_to_permissions_list()
    {
      $this->actingAsUser();
      $this->get('/permission')
         ->assertStatus(403);
    }

    /**
     * A test to show permission list to users with permission to view list.
     * @test
     * @return void
     */
    public function permit_user_with_permission_access_to_permissions_list()
    {
      $this->actingAsAdmin();
      $permOne = factory(Permission::class)->create();
      $permTwo = factory(Permission::class)->create();
      $this->get('/permission')
         ->assertSee($permOne->name)
         ->assertSee($permTwo->name);
    }

    /**
     * A test to create permission.
     * @test
     * @return void
     */
    public function permit_user_with_permission_edit_permission()
    {
      $this->actingAsAdmin();
      $newPerm = factory(Permission::class)->create();
      $newPerm->name = 'Manage Mentor';
      $this->put('/permission/'.$newPerm->id,$newPerm->toArray());
      $this->assertDatabaseHas('permissions',['id'=>$newPerm->id,'name'=>Str::slug($newPerm->name)]);
    }

    /**
     * A test to delete permission.
     * @test
     * @return void
     */
    public function permit_user_with_permission_delete_permission()
    {
      $this->actingAsAdmin();
      $newPerm = factory(Permission::class)->create(['name'=>'Deletable Permission','removable'=>1]);
      $this->delete('/permission/'.$newPerm->id);
      $this->assertDeleted($newPerm);
    }

    /**
     * A test to not delete default permission.
     * @test
     * @return void
     */
    public function default_permission_cannot_be_delete()
    {
      $this->actingAsAdmin();
      $newPerm = factory(Permission::class)->create(['name'=>'Deletable Permission']);
      $this->delete('/permission/'.$newPerm->id);
      $this->assertDatabaseHas('permissions',$newPerm->toArray());
    }

    private function actingAsAdmin()
    {
      $permission = factory(Permission::class)->create(['name'=>'manage-permission']);
      $role = factory(Role::class)->create(['name'=>'admin'])->givePermissionTo($permission->all());
      $user = factory(User::class)->create()->assignRole($role);
      return $this->actingAs($user);
    }

    private function actingAsUser()
    {
      $user = factory(User::class)->create();
      return $this->actingAs($user);
    }

}
