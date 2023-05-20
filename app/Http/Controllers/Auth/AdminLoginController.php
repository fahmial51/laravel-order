<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class AdminLoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/admin/home';
    protected $maxAttempts = 3;
    protected $decayMinutes = 1;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function guard()
    {
        return Auth::guard('admin');
    }

    public function showLoginForm()
    {
        return view('auth.admin_login');
    }

    /**
     * [login - ovveride login method form AuthenticatesUsers Traits]
     * @param  Request $request [Login Request]
     * @return [type]           [description]
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);

        if (auth()->attempt(['email' => $request->email, 'password' => $request->password])) {
            return redirect()->intended('home');
        }else{

            if ($this->hasTooManyLoginAttempts($request)) {

                $key = $this->throttleKey($request);
                $rateLimiter = $this->limiter();


                $limit = [3 => 1];
                $attempts = $rateLimiter->attempts($key);  // return how attapts already yet

                if($attempts >= 5)
                {
                    $rateLimiter->clear($key);;
                }

                if(array_key_exists($attempts, $limit)){
                    $this->decayMinutes = $limit[$attempts];
                }

                $this->incrementLoginAttempts($request);

                $this->fireLockoutEvent($request);
                return $this->sendLockoutResponse($request);

            }

            $this->incrementLoginAttempts($request);
            return $this->sendFailedLoginResponse($request);
        }

    }
}
