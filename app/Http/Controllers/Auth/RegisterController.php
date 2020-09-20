<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\URL;
use Auth;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        // ReCaptcha Validation
        if(setting('captcha')){
          return Validator::make($data,[
                    'fullname' => ['required', 'string', 'max:255','regex:(^([a-zA-Z_ ]+)([a-zA-Z]+)([a-zA-Z]+))'],
                    'username' => ['required', 'string', 'max:255', 'unique:users','regex:(^[a-zA-Z0-9_]+)'],
                    'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                    'password' => ['required', 'string', 'min:8', 'confirmed'],
                    'g-recaptcha-response' => 'required|captcha',
                ]);
        }

        return Validator::make($data, [
            'fullname' => ['required', 'string', 'max:255','regex:(^([a-zA-Z_ ]+)([a-zA-Z]+)([a-zA-Z]+))'],
            'username' => ['required', 'string', 'max:255', 'unique:users','regex:(^[a-zA-Z0-9_]+)'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
          $this->registered =  User::create([
              'fullname' => $data['fullname'],
              'username' => $data['username'],
              'email' => $data['email'],
              'password' => bcrypt($data['password']),
  			       'avatar' => URL::to('/')."/uploads/avatar/avatar.png",
          ]);

        $this->registered->assignRole('users');

		     // Logging activity for created role
		    activity()->performedOn($this->registered)->withProperties(['name'=>$this->registered->username,'by'=>$this->registered->username])->causedBy($this->registered)->log('Account was registered');
  		  return $this->registered;
    }
}
