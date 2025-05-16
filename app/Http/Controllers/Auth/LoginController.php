<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
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

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }
    public function username()
    {
        $login_type = filter_var(request()->input("email"), FILTER_VALIDATE_EMAIL) ? "email" : "username";

        request()->merge([
            $login_type => request()->input("email")
        ]);

        return $login_type;
    }
    protected function authenticated(Request $request, $user)
    {
        $info = getUserInfo(); // Ambil data user & waktu dari helper
        $pesanText = "🔐 *MIFA - Login Berhasil*\n\n";
        foreach ($info['nomorAdmins'] as $i => $nomor) {
            pesan($nomor, $pesanText, $info['token']);
            if ($i < count($info['nomorAdmins']) - 1) {
                //sleep(5);
                sleep(1);
            }
        }
    }
    protected function sendFailedLoginResponse(\Illuminate\Http\Request $request)
    {
        throw \Illuminate\Validation\ValidationException::withMessages([
            $this->username() => ['Maaf, data login tidak sesuai. Silakan coba lagi.'],
        ]);
    }
}
