<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

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

    protected $maxAttempts = 3;
    protected $decayMinutes = 1;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
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
