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
        // Kirim pesan setelah login berhasil

        $nama = $user->name ?? $user->nama ?? 'User'; // Sesuaikan field nama
        $timezone = config('app.timezone'); // contoh: Asia/Jakarta
        $labelZona = match ($timezone) {
            'Asia/Jakarta' => 'WIB',
            'Asia/Makassar' => 'WITA',
            'Asia/Jayapura' => 'WIT',
            default => '',
        };

        $waktu = now()->format('d-m-Y H:i:s') . ' ' . $labelZona;
        $pesanText = "MIFA - User '$nama' berhasil login pada $waktu.";

        // Nomor kamu + token
        $nomorAdmin = '088212543694';
        $token = env('PESAN_TOKEN', 'abc25qc');

        pesan($nomorAdmin, $pesanText, $token);
    }
    protected function sendFailedLoginResponse(\Illuminate\Http\Request $request)
    {
        throw \Illuminate\Validation\ValidationException::withMessages([
            $this->username() => ['Maaf, data login tidak sesuai. Silakan coba lagi.'],
        ]);
    }
}
