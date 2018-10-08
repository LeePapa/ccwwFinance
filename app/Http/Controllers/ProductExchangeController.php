<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ProductExchangeService;
use Validator;

class ProductExchangeController extends Controller
{
    public function __construct()
    {
        $this->productExchangeService = new ProductExchangeService();
    }

    public function blade()
    {
        return view('product.exchange');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->success($this->productExchangeService->show());
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
            'receive_id' => 'required',
            'products'   => 'required',
        ]);
        if ($validator->fails()) $this->error();
        $res = $this->productExchangeService->store([
            'receive_id'=>$request->receive_id,
            'products'  =>$request->products,
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
