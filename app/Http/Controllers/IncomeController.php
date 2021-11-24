<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Income;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class IncomeController extends Controller
{
    public function index()
    {
        $income = Income::with('branch')->get();

        $income->map(function ($each) {
            $created_by = explode(',', $each->created_by);
            $id = $created_by[0];
            $table = $created_by[1];

            if ($table == 'admin') {
                $user = Admin::findOrfail($id)->value('name');
            } else {
                $user = User::findOrfail($id)->value('name');
            }

            $each->created_by = $user;
        });

        $branches = Tenant::all();

        return view('/income', compact('income', 'branches'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'income_date' => 'required|date',
            'income' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            if (tenant()) {
                return redirect()->route('tenant.income', [tenant('id')])->with([
                    'errors' => $validator->errors()
                ]);
            } else {
                return redirect('/income')->with([
                    'errors' => $validator->errors()
                ]);
            }
        }

        if (auth('user')->check()) {
            $created_by = auth('user')->user()->id . ',user';
        } else {
            $created_by = auth('admin')->user()->id . ',admin';
        }

        Income::create([
            'tenant_id' => $request->branch ?: tenant('id'),
            'date' => $request->income_date,
            'income' => $request->income,
            'created_by' => $created_by
        ]);

        if (tenant()) {
            return redirect()->route('tenant.income', [tenant('id')]);
        } else {
            return redirect('/income');
        }
    }

    public function show($id)
    {
        $income = Income::with('branch:id,name')->findOrfail($id);

        return response()->json([
            'success' => true,
            'message' => 'Income Details',
            'data' => $income
        ]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'branch' => 'required',
            'income' => 'required|numeric',
            'income_date' => 'required|date'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ]);
        }

        Income::findOrfail($id)->update([
            'tenant_id' => $request->branch ?: tenant('id'),
            'income' => $request->income,
            'date' => $request->income_date
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Income updated!'
        ]);
    }
}
