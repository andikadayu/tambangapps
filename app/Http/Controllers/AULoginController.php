<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AULoginController extends Controller
{
    public function index(Request $request)
    {
        if (Session::get('isLogin')) {
            return redirect('dashboard');
        }
        return view('pages.login.login');
    }

    public function login(Request $request)
    {
        $username = $request->input('username');
        $password = $request->input('password');

        // check user
        $user = User::where('username', $username);
        $resp = [];

        // check if available
        if ($user->count() > 0) {
            $data = $user->first();

            // check password
            if (password_verify($password, $data->password)) {
                Session::put('isLogin', true);
                Session::put('name', $data->name);
                Session::put('userid', $data->id);
                Session::put('roles', $data->roles);
                $resp['status'] = true;
                $resp['msg'] = 'Login Success';
            } else {
                $resp['status'] = false;
                $resp['msg'] = 'Password Invalid';
            }
        } else {
            $resp['status'] = false;
            $resp['msg'] = 'Username Invalid';
        }
        return response($resp);
    }

    public function logout(Request $request)
    {
        Session::flush();
        return redirect('login');
    }
}
