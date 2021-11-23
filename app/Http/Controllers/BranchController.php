<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Order_items;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class BranchController extends Controller
{
    public function index()
    {
        $branches = Tenant::with('admin')->get();
        $branches->map(function ($branch) {
            $branch->makeHidden('id');
        });

        return view('branch', compact('branches'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'branch_name' => 'required|string'
        ]);

        if ($validator->fails()) {
            return redirect('branch')->with([
                'errors' => $validator->errors()
            ]);
        }

        $random = Str::random(5);

        $tenant = Tenant::create([
            'id' => $random,
            'name' => $request->branch_name,
        ]);

        $tenant->domains()->create(['domain' => $random . '.localhost']);

        return redirect('branch');
    }

    public function show($id)
    {
        $branch = Tenant::findOrfail($id);

        return response()->json([
            'success' => true,
            'message' => 'Branch Details',
            'data' => $branch
        ]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect('branch')->with([
                'errors' => $validator->errors()
            ]);
        }

        Tenant::findOrfail($id)->update([
            'name' => $request->name,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Branch updated'
        ]);
    }

    public function destroy($id)
    {
        $tenant = Tenant::findOrfail($id);

        User::where('tenant_id', $tenant->id)->delete();
        File::where('tenant_id', $tenant->id)->delete();
        Order_items::where('tenant_id', $tenant->id)->delete();

        $tenant->delete();

        return response()->json([
            'success' => true,
            'message' => 'Branch deleted'
        ]);
    }
}
