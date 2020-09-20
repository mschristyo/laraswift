<?php

namespace App\Http\Controllers\Auth;

use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Socialite;
use App\User;
use Google2FA;



class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;


    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Login username to be used by the controller.
     *
     * @var string
     */
    protected $username;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->username = $this->findUsername();
    }

    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateLogin(Request $request)
    {
        // ReCaptcha Validation
        if(setting('captcha')){
          return $request->validate([
                    $this->username() => 'required|string',
                    'password' => 'required|string',
                    'g-recaptcha-response' => 'required|captcha',
                ]);
        }

        return $request->validate([
                    $this->username() => 'required|string',
                    'password' => 'required|string',
                ]);
    }

    public function findUsername()
    {
        $login = request()->input('login');

        $fieldType = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        request()->merge([$fieldType => $login]);

        return $fieldType;
    }

    /*
    *  Get username property
    *
    *   @return string
    */
    public function username()
    {
        return $this->username;
    }
    /**
     * Signing out of session
     * @param  Request $request
     * @return url         redirect url
     */
    public function logout(Request $request) {

    		Google2FA::logout();
    		Auth::logout();
        return redirect('/');
    }


	/***************************SOCIALITE*****************************/
		/**
		 *Redirect the user to the GitHub authentication page.
		 *
		 * @return \Illuminate\Http\Response
		 */
		public function redirectToProvider($provider)
		{
			return Socialite::driver($provider)->redirect();
		}

		/**
		 * Obtain the user information from GitHub.
		 *
		 * @return \Illuminate\Http\Response
		 */
		public function handleProviderCallback($provider)
		{
			$user = Socialite::driver($provider)->user();
			$authUser = $this->findOrCreateUser($user, $provider);
			Auth::login($authUser, true);
			return redirect($this->redirectTo);
		}

		/**
		 * If a user has registered before using social auth, return the user
		 * else, create a new user object.
		 * @param  $user Socialite user object
		 * @param $provider Social auth provider
		 * @return  User
		 */
		public function findOrCreateUser($user, $provider)
		{
      $checker = [
        'email' => $user->getEmail(),
        'username' => $user->getNickname(),
        'provider' => $provider,
      ];
      // Check if the user is having any Socialite Login details
			$authUser = User::where('provider_id', $user->id)->first();

			if(empty($authUser) || is_null($authUser) ){

				$authUser = $this->createOrUpdate($user,$checker);
        $authUser->assignRole('users');

        /* Logging of activity of sign-up*/
        activity()->performedOn($authUser)
          ->withProperties(['name'=>($authUser->username)? $authUser->username:$authUser->email,'by'=>$authUser->fullname])
          ->causedBy($authUser)
          ->log('Account accessed with '.$provider);
        /* Logging of activity of sign-up*/
			}

			return $authUser;
		}

		private function getFullname($checker = null)
		{
      if($checker['provider']=='twitter'){
        return $fullname = User::whereUsername($checker['username'])->first()['fullname'];
      }

			return $fullname = User::whereEmail($checker['email'])->first()['fullname'];

		}

		private function getAvatar($checker = null)
		{
      if($checker['provider']=='twitter'){
        return User::whereUsername($checker['username'])->first()['avatar'];
      }
			return $avatar = User::whereEmail($checker['email'])->first()['avatar'];

		}

    private function getEmail($checker = null)
		{
      if($checker['provider']=='twitter'){
        return User::whereUsername($checker['username'])->first()['email'];
      }
			return User::whereEmail($checker['email'])->first()['email'];

		}

    private function getUsername($checker = null)
		{
      if($checker['provider']=='twitter'){
        return User::whereUsername($checker['username'])->first()['username'];
      }
			return User::whereEmail($checker['email'])->first()['username'];
		}

    private function fetchProviderAvatar($user,$provider)
    {
      if ($provider == 'facebook') {
          return  $user->avatar_original;
      }

      if ($provider == 'google') {
          return $user->getAvatar();
      }

      if ($provider == 'twitter') {

          if(isset($user->user['profile_image_url_https'])){

            return str_replace('_normal', '', $user->user['profile_image_url_https']);
          }

          return str_replace('_normal', '', $user->getAvatar());
      }

      return null;
    }


    private function createOrUpdate($data,$checker)
    {
      $fullname = is_null($this->getFullname($checker))? $data->getName(): $this->getFullname($checker);
      $avatar = is_null($this->getAvatar($checker)) ? $this->fetchProviderAvatar($data,$checker['provider']) : $this->getAvatar($checker);
      $email = is_null($this->getEmail($checker))? $data->getEmail() : $this->getEmail($checker);
      $username = is_null($this->getUsername($checker))? $data->getNickname() : $this->getUsername($checker);
      $user = User::where('username', '=' , $username)->get();

      if ($checker['provider']=='twitter') {
        return User::updateOrCreate(['username'=> $username],[
          'fullname' => $fullname,
      		'username' => $username,
      		'email' => $email,
      		'provider' => $checker['provider'],
      		'provider_id' => $data->id,
      		'avatar' => $avatar,
          ]);
      }

      return User::updateOrCreate(['email'=> $email],[
        'fullname' => $fullname,
        'username' => $username,
        'email' => $email,
        'provider' => $checker['provider'],
        'provider_id' => $data->id,
        'avatar' => $avatar,
      ]);
    }


	/***************************SOCIALITE*****************************/

}
