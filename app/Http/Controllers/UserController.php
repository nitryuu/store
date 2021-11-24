<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        $branches = Tenant::all();
        $branches->map(function ($branch) {
            $branch->makeHidden('id');
        });

        return view('user', compact('users', 'branches'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'branch' => "required",
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            if (tenant()) {
                return redirect('users', [tenant('id')])->with([
                    'errors' => $validator->errors()
                ]);
            } else {
                return redirect('users')->with([
                    'errors' => $validator->errors()
                ]);
            }
        }

        User::create([
            'tenant_id' => $request->branch,
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        if (tenant()) {
            return redirect('users', [tenant('id')]);
        } else {
            return redirect('users');
        }
    }

    public function show($id)
    {
        $user = User::findOrfail($id);

        return response()->json([
            'success' => true,
            'message' => 'User Details',
            'data' => $user
        ]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ]);
        }

        $user = User::findOrfail($id);
        $user->update([
            'name' => $request->name,
            'email' => $request->email
        ]);

        if ($request->password) {
            $user->update([
                'password' => bcrypt($request->password)
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'User updated'
        ]);
    }
}
