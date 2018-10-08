<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AgentService;
use Validator;

class AgentController extends Controller
{
    public function __construct()
    {
        $this->agentService = new AgentService();
    }

    public function blade()
    {
        return view('agent.agent');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->type == 'select') return $this->agents();
        return $this->success($this->agentService->show());
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
            'name' => 'required',
        ]);
        if ($validator->fails()) $this->error();
        $res = $this->agentService->store([
            'name'=>$request->name
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
            'name' => 'required',
        ]);
        if ($validator->fails()) $this->error();
        $res = $this->agentService->update($id ,[
            'name'=>$request->name
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
        $res = $this->agentService->delete($id);
        return $res ? $this->success() : $this->error();
    }

    public function agents()
    {
        return $this->success($this->agentService->agents());
    }
}
