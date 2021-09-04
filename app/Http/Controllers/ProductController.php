<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Product;
use App\Http\Requests\ProductRequest;

class ProductController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('createProduct',['products' => Product::all() ] );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('createProduct' );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\ProductRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store( ProductRequest $request)
    {
        $product = Product::create($request->all());
        return back()->with('success', 'Product dodany.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        return view('editProduct',['clients' => Client::all(), 'product' => $product ] );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\ProductRequest  $request
     * @param  int  $product
     * @return \Illuminate\Http\Response
     */
    public function update( ProductRequest $request, Product $product)
    {
        $product->update($request->all());
        return back()->with('success', 'Zmiany zapisane.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return redirect('/dashboard')->with('success', 'Produkt usunięty.');
    }
}
