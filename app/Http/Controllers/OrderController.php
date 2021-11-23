<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Category;
use App\Models\File;
use App\Models\Order;
use App\Models\Order_items;
use App\Models\Supplier;
use App\Models\Tenant;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File as Files;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function index()
    {
        if (!tenant()) {
            $orders = Order::with('branch')->get();
        } else {
            $orders = Order::with('branch')->where('status', 1)->get();
        }

        $orders->map(function ($order) {
            $created_by = explode(',', $order->created_by);
            $id = $created_by[0];
            $table = $created_by[1];

            if ($table == 'admin') {
                $user = Admin::findOrfail($id)->value('name');
            } else {
                $user = User::findOrfail($id)->value('name');
            }

            $order->created_by = $user;
        });

        $branches = Tenant::all();
        $branches->map(function ($branch) {
            $branch->makeHidden('id');
        });

        $suppliers = Supplier::all();
        $categories = Category::all();

        return view('data', compact('orders', 'branches', 'suppliers', 'categories'));
    }

    public function input()
    {
        $suppliers = Supplier::all();
        $categories = Category::all();
        $branches = Tenant::all();
        $branches->map(function ($branch) {
            $branch->makeHidden('id');
        });

        return view('input', compact('suppliers', 'categories', 'branches'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'branch' => 'sometimes|required',
            'name' => 'required|array',
            'qty' => 'required|array',
            'price' => 'required|array',
            'category' => 'required|numeric',
            'subtotal' => 'required|array',
            'total' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return redirect('/order-input')->with([
                'errors' => $validator->errors()
            ]);
        }

        if (auth('user')->check()) {
            $created_by = auth('user')->user()->id . ',user';
        } else {
            $created_by = auth('admin')->user()->id . ',admin';
        }

        $order = Order::create([
            'tenant_id' => $request->branch ?: tenant('id'),
            'category_id' => $request->category,
            'supplier_id' => $request->supplier ?: 0,
            'transaction_id' => $request->transaction_id ?: 0,
            'grand_total' => $request->total,
            'status' => 1,
            'date' => $request->date ?: Carbon::now(),
            'created_by' => $created_by
        ]);

        for ($i = 0; $i < count($request->name); $i++) {
            if ($request->name[$i]) {
                Order_items::create([
                    'tenant_id' => $request->branch,
                    'order_id' => $order->id,
                    'name' => $request->name[$i],
                    'qty' => $request->qty[$i],
                    'price' => $request->price[$i],
                    'subtotal' => $request->subtotal[$i]
                ]);
            }
        }

        if ($request->hasFile('files')) {
            for ($j = 0; $j < count($request->file('files')); $j++) {
                if (!Files::exists(public_path('/assets/'))) {
                    Files::makeDirectory(public_path('/assets/'), 0777, true);
                    $filename = $order->id . '_' . ($j + 1) . '.' . $request->file('files')[$j]->extension();
                    $request->file('files')[$j]->move(public_path('/assets'), $filename);
                } else {
                    $filename = $order->id . '_' . ($j + 1) . '.' . $request->file('files')[$j]->extension();
                    $request->file('files')[$j]->move(public_path('/assets'), $filename);
                }

                File::create([
                    'tenant_id' => $request->branch,
                    'order_id' => $order->id,
                    'filename' => $filename
                ]);
            }
        }

        return redirect('/order-input');
    }

    public function show($id)
    {
        $order = Order::with(['branch', 'supplier', 'category'])->findOrfail($id);
        $files = File::where('tenant_id', $order->tenant_id)->where('order_id', $id)->get();

        $orderItems = Order_items::where('order_id', $id)->where('tenant_id', $order->tenant_id)->get();

        return response()->json([
            'success' => true,
            'message' => 'Order Details',
            'order' => $order,
            'items' => $orderItems,
            'files' => $files
        ]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'branch' => 'sometimes|required',
            'name' => 'required|array',
            'qty' => 'required|array',
            'price' => 'required|array',
            'category' => 'required|numeric',
            'subtotal' => 'required|array',
            'total' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return redirect('/order-input')->with([
                'errors' => $validator->errors()
            ]);
        }

        $order = Order::findOrfail($id);
        $order->update([
            'tenant_id' => $request->branch,
            'category_id' => $request->category,
            'supplier_id' => $request->supplier ?: 0,
            'transaction_id' => $request->transaction_id ?: 0,
            'grand_total' => $request->total,
            'date' => $request->date ?: Carbon::now()
        ]);

        for ($i = 0; $i < count($request->name); $i++) {
            if ($request->name) {
                Order_items::where('tenant_id', $request->branch)->where('order_id', $id)->update([
                    'tenant_id' => $request->branch,
                    'order_id' => $order->id,
                    'name' => $request->name[$i],
                    'qty' => $request->qty[$i],
                    'price' => $request->price[$i],
                    'subtotal' => $request->subtotal[$i]
                ]);
            }
        }

        if ($request->hasFile('files')) {
            for ($j = 0; $j < count($request->file('files')); $j++) {
                if (!Files::exists(public_path('/assets/'))) {
                    Files::makeDirectory(public_path('/assets/'), 0777, true);
                    $filename = $order->id . '_' . ($j + 1) . '.' . $request->file('files')[$j]->extension();
                    $request->file('files')[$j]->move(public_path('/assets'), $filename);
                } else {
                    $filename = $order->id . '_' . ($j + 1) . '.' . $request->file('files')[$j]->extension();
                    $request->file('files')[$j]->move(public_path('/assets'), $filename);
                }

                File::where('tenant_id', $request->branch)->where('order_id', $order->id)->update([
                    'filename' => $filename
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Order updated'
        ]);
    }

    public function destroy($id)
    {
        if (!tenant()) {
            Order::findOrfail($id)->delete();
            Order_items::where('order_id', $id)->delete();
        } else {
            Order::findOrfail($id)->update([
                'status' => 0
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Order deleted'
        ]);
    }
}
