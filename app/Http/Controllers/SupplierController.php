<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $suppliers = Supplier::all();

        return view('supplier', compact('suppliers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'address' => 'required',
            'phone_number' => 'required',
            'email' => 'required|email|unique:suppliers,email'
        ]);

        if ($validator->fails()) {
            return view('supplier')->with([
                'errors' => $validator->errors()
            ]);
        }

        Supplier::create([
            'name' => $request->name,
            'address' => $request->address,
            'phone_number' => $request->phone_number,
            'email' => $request->email
        ]);

        if (tenant()) {
            return redirect()->route('tenant.supplier', [tenant('id')]);
        } else {
            return redirect('/supplier');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $supplier = Supplier::findOrfail($id);

        return response()->json([
            'success' => true,
            'message' => 'Supplier Details',
            'data' => $supplier
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
        ]);

        if ($validator->fails()) {
            if (tenant()) {
                return redirect()->route('tenant.supplier', [tenant('id')])->with([
                    'errors' => $validator->errors()
                ]);
            } else {
                return redirect('supplier')->with([
                    'errors' => $validator->errors()
                ]);
            }
        }

        Supplier::findOrfail($id)->update([
            'name' => $request->name,
            'address' => $request->address,
            'phone_number' => $request->phone_number,
            'email' => $request->email
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Supplier updated'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Supplier::findOrfail($id)->delete();
        Order::where('supplier_id', $id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Supplier deleted'
        ]);
    }
}
