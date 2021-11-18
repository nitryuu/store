<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class BranchController extends Controller
{
    public function index()
    {
        if (tenant()) {
            return redirect('/');
        }

        $branches = Tenant::all();
        $branches->map(function ($branch) {
            $branch->makeHidden('id');
        });

        return view('branch', compact('branches'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string'
        ]);

        if ($validator->fails()) {
            return redirect('branch')->with([
                'errors' => $validator->errors()
            ]);
        }

        $random = Str::random(5);

        $tenant = Tenant::create([
            'id' => $random,
            'name' => $request->name
        ]);

        $tenant->domains()->create(['domain' => $random . '.localhost']);

        return redirect('branch');
    }
}
