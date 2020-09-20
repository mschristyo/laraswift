<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\URL;
use App\User;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = User::create([
        	'fullname'=>'LaraSwift Admin',
        	'email' => 'admin@gmail.com',
        	'username' => 'admin',
        	'password' => bcrypt('Password1'),
			    'avatar' => URL::to('/')."/uploads/avatar/avatar.png",
          'email_verified_at' => time(),
        ]);
        $admin->assignRole('admin');

        $admin = User::create([
        	'fullname'=>'John Doe',
        	'email' => 'johndoe@gmail.com',
        	'username' => 'johndoe',
        	'password' => bcrypt('Password1'),
			    'avatar' => URL::to('/')."/uploads/avatar/avatar.png",
          'email_verified_at' => time(),
        ]);
        $admin->assignRole('users');

    }
}
