<?php

namespace App\Http\Controllers;

// use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// use PragmaRX\Google2FA\Google2FA;
use Auth;
use App\Google2fa as TwoFactor;
use Google2FA;
use Hash;

class Google2faController extends Controller
{
    public function __contruct()
    {
        if (setting('email_verification')) {
            $this->middleware(['verified']);
        }
    }

    public function index()
    {
        $user = Auth::user();
        $generated = '';

        if (!empty($user->google2fa)) {
            $google2fa = app('pragmarx.google2fa');
            $generated = $google2fa->getQRCodeInline(
                config('app.name'),
                auth()->user()->fullname,
                $user->google2fa->google2fa_secret
            );
        }

        return view('google2fa.index', [
                                    'generated' => $generated,
                                    'user' => $user
                            ]);
    }

    /**
    * Generate QRCode Two Factor Authentication
    * @return string
    */
    public function activate()
    {
        $user = Auth::user();
        $google2fa = app('pragmarx.google2fa');

        $google2fa = $google2fa->generateSecretKey();
        TwoFactor::create([
                                       'user_id' => $user->id,
                                       'google2fa_enable' => 0,
                                       'google2fa_secret' => $google2fa
                            ]);

        return redirect()->back()->with('success', '2-Factor Activated');
    }

    /**
    * Enable Two Factor Authentication
    * @param string $request
    * @return string
    */
    public function enable(Request $request)
    {
        $this->validate($request, [
                                            'code' => 'required',
                                    ]);

        $user = Auth::user();
        $google2fa = app('pragmarx.google2fa');
        $verified = $google2fa->verifyKey($user->google2fa->google2fa_secret, $request->code);

        if ($verified) {
            $user->google2fa->google2fa_enable = 1;
            $user->google2fa->save();
            return redirect()->back()->with('success', '2-Factor Enabled');
        }

        return redirect()->back()->with('fail', 'Verification Code is Invalid');
    }

    /**
    * Disable active Two Factor Authentication
    * @param string $request
    * @return string
    */
    public function disable(Request $request)
    {
        $this->validate($request, [
                                    'code' => 'required',
                                    'password' => 'required',
                        ]);

        $user = Auth::user();
        $google2fa = app('pragmarx.google2fa');

        if (Hash::check($request->password, $user->password)) {
            $verified = $google2fa->verifyKey($user->google2fa->google2fa_secret, $request->code);

            if ($verified) {
                $user->google2fa->delete();
                return redirect()->back()->with('success', '2-Factor Disabled');
            }

            return redirect()->back()->with('fail', 'Verification Code is Invalid');
        } else {
            return redirect()->back()->with('fail', 'Invalid Password! Check Password and try again');
        }
    }


    /**
    * Disable active Two Factor Authentication
    * @param string $request
    * @return string
    */
    public function instruction()
    {
        return view('google2fa.instruction');
    }
}
