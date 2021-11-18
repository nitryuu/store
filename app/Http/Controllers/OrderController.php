<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\File;
use App\Models\Order;
use App\Models\Order_items;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File as Files;
use Illuminate\Support\Facades\Validator;

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
        $categories = Category::all();

        return view('input', compact('suppliers', 'categories'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|array',
            'qty' => 'required|array',
            'price' => 'required|array',
            'category' => 'required|numeric',
            'subtotal' => 'required|array',
            'total' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            dd($validator->errors());
            return redirect('/order-input')->with([
                'errors' => $validator->errors()
            ]);
        }

        $order = Order::create([
            'category_id' => $request->category,
            'supplier_id' => $request->supplier ?: 0,
            'transaction_id' => $request->transaction_id ?: 0,
            'grand_total' => $request->total,
        ]);

        if ($request->date) {
            $order->created_at = $request->date;
            tenant('id') && $order->tenant_id = tenant('id');
            $order->update();
        }

        for ($i = 0; $i < count($request->name); $i++) {
            if ($request->name[$i]) {
                Order_items::create([
                    'order_id' => $order->id,
                    'name' => $request->name[$i],
                    'qty' => $request->qty[$i],
                    'harga' => $request->price[$i],
                    'subtotal' => $request->subtotal[$i]
                ]);
            }
        }

        for ($j = 0; $j < count($request->file('files')); $j++) {
            if (!Files::exists(public_path('/assets/'))) {
                Files::makeDirectory(public_path('/assets/'), 0777, true);
                $filename = $order->id . '_' . $j + 1 . '.' . $request->file('files')[$j]->extension();
                $request->file('files')[$j]->move(public_path('/assets'), $filename);
            } else {
                $filename = $order->id . '_' . $j + 1 . '.' . $request->file('files')[$j]->extension();
                $request->file('files')[$j]->move(public_path('/assets'), $filename);
            }

            File::create([
                'order_id' => $order->id,
                'filename' => $filename
            ]);
        }

        return redirect('/order-input');
    }
}
