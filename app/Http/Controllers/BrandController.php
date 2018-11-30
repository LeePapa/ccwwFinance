<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\BrandService;
use Validator;

class BrandController extends Controller
{
    protected $brandService;
    
    public function __construct()
    {
        $this->brandService = new BrandService();
    }

    public function blade()
    {
        return view('brand.brand');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->type == 'select') return $this->brands();
        return $this->success($this->brandService->show($request->all()));
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
            'brand_name' => 'required',
        ]);
        if ($validator->fails()) $this->error();
        $res = $this->brandService->store([
            'brand_name'=>$request->brand_name
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
        $validator = Validator::make($request->all(),[
            'brand_name' => 'required',
        ]);
        if ($validator->fails()) $this->error();
        $res = $this->brandService->update($id ,[
            'brand_name'=>$request->brand_name
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
        $res = $this->brandService->delete($id);
        return $res ? $this->success() : $this->error();
    }

    public function brands()
    {
        return $this->success($this->brandService->brands());
    }
}
