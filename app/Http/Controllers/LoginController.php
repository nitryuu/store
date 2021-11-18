<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{

    public function index()
    {
        if (auth('admin')->check()) {
            return redirect('/dashboard');
        }

        return view('login');
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect('login')->with([
                'errors' => $validator->errors()
            ]);
        }

        $adminCheck = auth('admin')->attempt($request->only('email', 'password'));

        if ($adminCheck) {
            return redirect('dashboard');
        } else {
            return redirect('login')->with([
                'errors' => 'Email atau Password Anda Salah'
            ]);
        }
    }

    public function branchLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect('login')->with([
                'errors' => $validator->errors()
            ]);
        }

        $branchCheck = auth('user')->attempt($request->only('email', 'password'));

        if ($branchCheck) {
            $tenant = Tenant::find(auth('user')->user()->tenant_id);
            $domain = $tenant->domains()->first()->domain;
            return redirect()->route('dashboard')->domain($domain);
        } else {
            return redirect('login');
        }
    }

    public function logout()
    {
        auth('admin')->logout();

        return redirect('login');
    }
}
