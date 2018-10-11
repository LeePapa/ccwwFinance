<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserService;
use Validator;

class UserController extends Controller
{
    public function __construct()
    {
        $this->userService = new UserService();
    }

    public function blade()
    {
        return view('user.user');
    }

    public function loginBlade()
    {
        return view('user.login');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->type == 'select') return $this->users($request);
        return $this->success($this->userService->show($request->all()));
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
            'phone'   => 'required',
            'agent_id'=>'required'
        ]);
        if ($validator->fails()) $this->error();
        $res = $this->userService->store([
            'phone'   =>$request->phone,
            'username'=>$request->username,
            'weixin'  =>$request->weixin,
            'agent_id'=>$request->agent_id,
        ]);
        return $res ? $this->success() : $this->error('该手机已使用！');
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
            'phone'   => 'required',
            'agent_id'=>'required'
        ]);
        if ($validator->fails()) $this->error();
        $res = $this->userService->update($id ,[
            'phone'   =>$request->phone,
            'username'=>$request->username,
            'weixin'  =>$request->weixin,
            'agent_id'=>$request->agent_id,
        ]);
        return $res ? $this->success() : $this->error('该手机已使用！');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return $this->success($this->userService->delete($id));
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'phone'   => 'required',
            'password'=>'required'
        ]);
        if ($validator->fails()) $this->error();

        $res = $this->userService->login([
            'phone'=>$request->phone,
            'password'=>$request->password
        ]);

        return $res ? $this->success('登录成功') : $this->error('账号密码错误');
    }

    public function users(Request $request)
    {
        return $this->success($this->userService->users());
    }
}
