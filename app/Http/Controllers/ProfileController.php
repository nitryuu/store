<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function index()
    {
        $adminCheck = auth('admin')->check();

        if ($adminCheck) {
            $profile = Admin::findOrfail(auth('admin')->user()->id);
        } else {
            $profile = User::findOrfail(auth('user')->user()->id);
        }

        return view('profile', compact('profile'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => "required|email"
        ]);

        if ($validator->fails()) {
            return redirect('profile')->with([
                'errors' => $validator->errors()
            ]);
        }

        $adminCheck = auth('admin')->check();

        if ($adminCheck) {
            $user = Admin::findOrfail(auth('admin')->user()->id);
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);

            if ($request->password) {
                $user->update([
                    'password' => bcrypt($request->password)
                ]);
            }
        } else {
            $user = User::findOrfail(auth('user')->user()->id);
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);

            if ($request->password) {
                $user->update([
                    'password' => bcrypt($request->password)
                ]);
            }
        }

        return redirect('profile');
    }
}
