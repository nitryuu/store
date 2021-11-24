<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::all();

        return view('category', compact('categories'));
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
            'name' => 'required|string'
        ]);

        if ($validator->fails()) {
            if (tenant()) {
                return redirect()->route('tenant.category', [tenant('id')])->with([
                    'errors' => $validator->errors()
                ]);
            } else {
                return redirect('/category')->with([
                    'errors' => $validator->errors()
                ]);
            }
        }

        Category::create([
            'name' => $request->name
        ]);

        if (tenant()) {
            return redirect()->route('tenant.category', [tenant('id')]);
        } else {
            return redirect('/category');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $category = Category::findOrfail($id);

        return response()->json([
            'success' => true,
            'message' => 'Category Details',
            'data' => $category
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
        ]);

        if ($validator->fails()) {
            if (tenant()) {
                return redirect()->route('tenant.category', [tenant('id')])->with([
                    'errors' => $validator->errors()
                ]);
            } else {
                return redirect('category')->with([
                    'errors' => $validator->errors()
                ]);
            }
        }

        Category::findOrfail($id)->update([
            'name' => $request->name,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Category updated'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = Category::findOrfail($id);
        Order::where('category_id', $id)->delete();
        $category->delete();

        return response()->json([
            'success' => true,
            'message' => 'Category deleted'
        ]);
    }
}
