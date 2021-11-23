<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Tenant;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $branches = Tenant::count();
        return view('dashboard', compact('branches'));
    }

    public function chart()
    {
        $branches = Tenant::with(['orders' => function ($query) {
            $query->select('tenant_id', DB::raw('SUM(grand_total) as outcome'))->whereMonth('date', Carbon::now()->format('m'))->groupBy('tenant_id');
        }, 'income' => function ($query) {
            $query->select('tenant_id', DB::raw('SUM(income) as income'))->whereMonth('date', Carbon::now()->format('m'))->groupBy('tenant_id');
        }])->select('id', 'name')->get();

        return response()->json([
            'success' => true,
            'message' => 'Chart Data',
            'data' => $branches
        ]);
    }

    public function filter_chart(Request $request)
    {
        $date = $request->date;
        $branches = Tenant::with(['orders' => function ($query) use ($date) {
            $query->select('tenant_id', DB::raw('SUM(grand_total) as outcome'))->whereDate('date', $date)->groupBy('tenant_id');
        }, 'income' => function ($query) use ($date) {
            $query->select('tenant_id', DB::raw('SUM(income) as income'))->whereDate('date', $date)->groupBy('tenant_id');
        }])->select('id', 'name')->get();

        return response()->json([
            'success' => true,
            'message' => 'Chart Data',
            'data' => $branches
        ]);
    }
}
