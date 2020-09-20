<?php

namespace Tests\Feature\Controller;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\User;
use Faker\Generator as Faker;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A test to redirect to login when user is not authenticated.
     * @test
     * @return void
     */
    public function authentication_of_users_to_access_users_list()
    {
        $this->get('/user')
           ->assertRedirect('/login');
    }

    /**
     * A test for users without manage-user permission.
     * @test
     * @return void
     */
    public function regular_user_cannot_access_users_list()
    {
        $this->actingAsUser();
        $this->get('/user')
           ->assertStatus(403);
    }

    /**
     * A test for users with manage-user permission access users list.
     * @test
     * @return void
     */
    public function permitted_user_can_access_users_list()
    {
        $this->actingAsAdmin();
        $firstUser = factory(User::class)->create();
        $secondUser = factory(User::class)->create();
        $this->get('/user')
           ->assertSee($firstUser->fullname)
           ->assertSee($firstUser->email)
           ->assertSee($secondUser->fullname)
           ->assertSee($secondUser->email);
    }

    /**
     * A test for users with manage-user permission access a single user.
     * @test
     * @return void
     */
    public function accessing_a_user_details()
    {
        $this->actingAsAdmin();
        $firstUser = factory(User::class)->create();
        $this->get('/user'.'/'.$firstUser->id.'/edit')
           ->assertSee($firstUser->fullname)
           ->assertSee($firstUser->email);
    }

    /**
     * A test for user creation by admin
     * @test
     * @return void
     */
    public function create_user_by_admin()
    {
      $this->actingAsAdmin();

      $role = factory(Role::class)->create(['name'=>'user']);
      $data = $this->createUserStud();
      $data['role'] = $role->name;
      $this->post('user',$data)
         ->assertRedirect();
    }

    /**
     * A test for editing users by admin
     * @test
     * @return void
     */
    public function edit_user_by_admin()
    {
      $this->actingAsAdmin();

      $user = factory(User::class)->create();
      $user->fullname = 'Plaintain Testing';
      $user->save();
      $this->actingAs($user)->put('/user/'.$user->id,$user->toArray());
      $this->assertDatabaseHas('users',['id'=>$user->id,'fullname'=>$user->fullname]);
    }

    /**
     * A test for deleting users by admin
     * @test
     * @return void
     */
    public function delete_user_by_admin()
    {
      $this->actingAsAdmin();
      $user = factory(User::class)->create();
      $this->delete('/user/'.$user->id,$user->toArray());
      $this->assertDeleted('users',['id'=>$user->id,'email'=>$user->email]);
    }

    private function actingAsAdmin()
    {
      $permission = factory(Permission::class)->create(['name'=>'manage-user']);
      $role = factory(Role::class)->create(['name'=>'admin'])->givePermissionTo($permission->all());
      $user = factory(User::class)->create()->assignRole($role);
      return $this->actingAs($user);
    }

    private function actingAsUser()
    {
      $user = factory(User::class)->create();
      return $this->actingAs($user);
    }

    private function createUserStud()
    {
      $faker = \Faker\Factory::create();

      return [
        'fullname' => $faker->name,
        'username' => $faker->userName,
        'email'=> $faker->unique()->email,
        'birthday' => '1999-06-15',
        'country' => 'Togo',
        'address' => 'Lara-Swift Estate, Lome, Togo',
        'phone' => '28894865433',
        'password' => 'Password1',
        'password_confirmation' => 'Password1'
      ];
    }
}
