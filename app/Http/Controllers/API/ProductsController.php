<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $product = new Product();
        $request->validate(['img' => 'image|max:2048']);
        $img = $request->file('img');
        $route = 'images/users/';
        $imgName = time() . '-' . str_replace(' ', '', $img->getClientOriginalName());
        $request->file('img')->move($route, $imgName);
        
        $product->name = $request->name;
        $product->brand = $request->brand;
        $product->price = $request->price;
        $product->img = 'images/users/' . $imgName;
        try{
            if ($product->save() >= 1) {
                return response()->json(['status' => 'OK', 'data' => $product], 201);
            }
        }catch(\Exception $e){
            return response()->json(["message" => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $product = Product::select(
            'products.id',
            'products.name',
            'products.brand',
            'products.price',
            'products.img'
        )
        ->orderBy('id', 'desc')
        ->get();
        return $product;
        
    }

    public function showProducts()
    {
        $product = Product::select(
            'products.id',
            'products.name',
            'products.brand',
            'products.price',
            'products.img'
        )
        ->orderBy('id', 'desc')
        ->get();
        return view('home', ['products' => $product]);
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        // $data = Product::where('id',$request->id)->update(['name'=>$request->name,'brand'=>$request->brand, 'price'=>$request->price]);
        // return response()->json(['status' => 'OK', 'data' => $data], 201);
        
        $product = Product::FindOrFail($request->id);
        $route = 'images/users/';
        unlink($route . $product->img);
        $request->validate(['img' => 'image|max:2048']);
        $img = $request->file('img');
        $route = 'images/users/';
        $imgName = time() . '-' . str_replace(' ', '', $img->getClientOriginalName());
        $request->file('img')->move($route, $imgName);

        $product->name = $request->name;
        $product->brand = $request->brand;
        $product->price = $request->price;
        $product->img = 'images/users/' . $imgName;
        try{
            if ($product->save() >= 1) {
                return response()->json(['status' => 'OK', 'data' => $product], 201);
            }
        }catch(\Exception $e){
            return response()->json(["message" => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $product = Product::FindORFail($request->id);
        $route = 'images/users/';
        unlink($route . $product->img);
        $product->delete();
        return response()->json(['message' => 'Deleted successfully'], 200);
    }
}
