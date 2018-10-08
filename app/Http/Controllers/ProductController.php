<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ProductService;
use Validator;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->productService = new ProductService();
    }

    public function blade()
    {
        return view('product.product');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->type == 'select') return $this->products($request);
        return $this->success($this->productService->show());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'brand_id' => 'required',
            'name'     => 'required',
            'stock'    => 'required',
        ]);
        if ($validator->fails()) $this->error();
        $res = $this->productService->store([
            'brand_id'=>$request->brand_id,
            'name'    =>$request->name,
            'stock'   =>$request->stock,
            'prices'  =>$request->prices,
        ]);
        return $res ? $this->success() : $this->error('该名称已使用！');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $this->success($this->productService->read($id));
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
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(),[
            'brand_id' => 'required',
            'name'     => 'required',
            'stock'    => 'required',
        ]);
        if ($validator->fails()) $this->error();
        $res = $this->productService->update($id ,[
            'brand_id'=>$request->brand_id,
            'name'    =>$request->name,
            'stock'   =>$request->stock,
            'prices'  =>$request->prices,
        ]);
        return $res ? $this->success() : $this->error('该名称已使用！');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $res = $this->productService->delete($id);
        return $res ? $this->success() : $this->error();
    }

    public function products(Request $request)
    {
        return $this->success($this->productService->products([
            'brand_id'=>$request->brand_id
        ]));
    }
}
