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

class RoleControllerTest extends TestCase
{
  use RefreshDatabase;

  /**
   * A test to redirect un-authenticated user to login page.
   * @test
   * @return void
   */
  public function authentication_of_user_to_access_roles_list()
  {
    $this->get('/role')
       ->assertRedirect('/login');
  }

  /**
   * A test to restrict users without permission to access roles list.
   * @test
   * @return void
   */
  public function restrict_regular_user_without_permission_access_to_roles_list()
  {
    $this->actingAsUser();
    $this->get('/role')
       ->assertStatus(403);
  }

  /**
   * A test to show permission list to users with permission to view list.
   * @test
   * @return void
   */
  public function permit_user_with_permission_access_to_roles_list()
  {
    $this->actingAsAdmin();
    $roleOne = factory(Role::class)->create();
    $roleTwo = factory(Role::class)->create();
    $this->get('/role')
       ->assertSee($roleOne->name)
       ->assertSee($roleTwo->name);
  }

  /**
   * A test to create permission.
   * @test
   * @return void
   */
  public function permit_user_with_permission_edit_role()
  {
    $this->actingAsAdmin();
    $newRole = factory(Role::class)->create();
    $newRole->name = 'Manage Mentor';
    $this->put('/role/'.$newRole->id,$newRole->toArray());
    $this->assertDatabaseHas('roles',['id'=>$newRole->id,'name'=>Str::slug($newRole->name)]);
  }

  /**
   * A test to delete permission.
   * @test
   * @return void
   */
  public function permit_user_with_permission_delete_role()
  {
    $this->actingAsAdmin();
    $newRole = factory(Role::class)->create(['name'=>'Deletable Role','removable'=>1]);
    $this->delete('/role/'.$newRole->id);
    $this->assertDeleted($newRole);
  }

  /**
   * A test to not delete default permission.
   * @test
   * @return void
   */
  public function default_permission_cannot_be_delete()
  {
    $this->actingAsAdmin();
    $newRole = factory(Role::class)->create(['name'=>'Deletable Role']);
    $this->delete('/role/'.$newRole->id);
    $this->assertDatabaseHas('roles',$newRole->toArray());
  }
  /**
   * @test
   * @return void
   */
  public function assigning_permissions_to_a_role()
  {
    $this->actingAsAdmin();
    $permOne = factory(Permission::class)->create();
    $role = factory(Role::class)->create()->givePermissionTo($permOne->all());
    $permissions = factory(Role::class)->times(5)->create();
    $data = collect([]);

    foreach($permissions as $permission){
        $data->push($permission->name);
    }
    
    $this->post('role-permission/'.$role->id,$data->toArray())
    ->assertStatus(302);
  }

  private function actingAsAdmin()
  {
    $permission = factory(Permission::class)->create(['name'=>'manage-role']);
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
