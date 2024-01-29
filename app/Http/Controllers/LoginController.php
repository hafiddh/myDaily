<?php

namespace App\Http\Controllers;

use App\User;
use App\VerifyUser;
use App\Mail\VerifikasiUlangEmail;
use App\Mail\VerifikasiEmail;
use App\Mail\ResetPassword;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Auth;
use Mail;
use DB;
use Carbon\Carbon;;

class LoginController extends Controller
{


    function get_client_ip()
    {
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if (getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if (getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if (getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if (getenv('HTTP_FORWARDED'))
            $ipaddress = getenv('HTTP_FORWARDED');
        else if (getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }

    public function hal_login()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        // dd($request);
        $usernameinput = $request->email;
        $password = $request->password;

        $field = filter_var($usernameinput, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if (Auth::attempt([$field => $usernameinput, 'password' => $password], true)) {
            if (Auth::user()->email_verified_at == null) {
                // dd(auth()->user()->email);

                $ambil = DB::table('users')
                    ->where('users.email', auth()->user()->email)
                    ->leftjoin('verify_users', 'users.id', 'verify_users.user_id')
                    ->get();

                Mail::to($ambil[0]->email)->send(new VerifikasiUlangEmail($ambil[0]));

                activity()->withProperties(['ip' => $this->get_client_ip()])->log('Kirim ulang verivikasi');

                Auth::logout();
                return \redirect(route('login'))->with('message', 'Akun belum diverifikasi, Email verifikasi telah dikirim ulang, Silakan cek email anda');
            }
            
            activity()->withProperties(['ip' => $this->get_client_ip()])->log('Login');

            if (auth()->user()->level == 2) {
                // dd('1');
                return \redirect(route('admin.index'));
            } else if (auth()->user()->level == 1) {
                // dd('2');
                return \redirect(route('web.index'));
            } else {
                return \redirect(route('login'));
            }
        } else {
            return \redirect()->back()->with('error', 'Username atau Password Salah!');
        }
    }


    public function register(Request $request)
    {

        // dd($request);
        $cek = DB::table('users')->where('email', $request->email)->count();
        if ($cek != 0) {
            // dd('skip');
            $ambil = DB::table('users')
                ->where('users.email', $request->email)
                ->leftjoin('verify_users', 'users.id', 'verify_users.user_id')
                ->get();

            Mail::to($ambil[0]->email)->send(new VerifikasiUlangEmail($ambil[0]));

            activity()->withProperties(['ip' => $this->get_client_ip()])->log('Kirim ulang verivikasi');
            return \redirect(route('login'))->with('success', 'Email anda telah terdaftar, verifikasi telah dikirim, silakan periksa email anda kembali!');
        } else {
            // dd('ndd');
            $user = User::create([
                'nama' => $request->username,
                'email' => $request->email,
                'username' => $request->username,
                'level' => '1',
                'password' => bcrypt($request->password),
            ]);

            VerifyUser::Create([
                'token' => Str::random(60),
                'user_id' => $user->id,
            ]);

            // dd($user);

            activity()->withProperties(['ip' => $this->get_client_ip()])->log('Buat Akun');

            Mail::to($user->email)->send(new VerifikasiEmail($user));
        }

        return \redirect(route('login'))->with('success', 'Email verifikasi terkirim, silakan periksa email anda!');
    }

    public function verifyEmail($token)
    {
        $verifiedUser  = VerifyUser::where('token', $token)->first();
        if (isset($verifiedUser)) {
            $user = $verifiedUser->user;
            if (!$user->email_verified_at) {
                $user->email_verified_at = Carbon::now();
                $user->save();
                return \redirect(route('login'))->with('success', 'Email anda sudah diverifikasi! Silakan melakukan login ke aplikasi!');
            } else {
                return \redirect()->back()->with('info', 'Email anda sudah diverifikasi sebelumnya. Silakan melakukan login ke aplikasi!');
            }
        } else {
            return \redirect(route('login'))->with('error', 'ada error');
        }
    }

    public function verifyPassword($token)
    {
        // $verifiedUser  = VerifyUser::where('token', $token)->first();
        $verif = DB::table('verify_users')
            ->where('verify_users.token', $token)
            ->leftjoin('users', 'users.id', 'verify_users.user_id')
            ->first();

        $gas = DB::table('users')->where('id', $verif->id)->update([
            'password' => bcrypt($verif->email)
        ]);

        return \redirect(route('login'))->with('success', 'Password anda berhasil direset! Silakan melakukan login ke aplikasi!');
        // dd($token, $verif);
    }

    public function logout(Request $request)
    {

        activity()->withProperties(['ip' => $this->get_client_ip()])->log('Logout');

        Auth::logout();

        return \redirect(route('login'))->with('success', 'Anda berhasil logout!');
    }



    public function bcrypt_gen()
    {
        // $x = password_hash('7471010108970002', PASSWORD_DEFAULT);
        // $y = password_verify('7471010108970002', '$2y$10$5WclQlqzaU099C7ngRrC5.bHEG4i3n9GjA4M3P89H3fHTwO05ycwu');
        // $x = bcrypt('bungku_barat');
        dd($x);
    }
}
