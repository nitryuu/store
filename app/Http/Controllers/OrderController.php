<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Supplier;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::all();

        return view('data', compact('orders'));
    }

    public function input()
    {
        $suppliers = Supplier::all();

        return view('input', compact('suppliers'));
    }

    public function store(Request $request)
    {
        dd($request->all());
    }
}
