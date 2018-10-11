<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use EasyWeChat;

class WeChatController extends Controller
{
    public function server()
    {
        $app = EasyWeChat::officialAccount();

        $app->server->push(function ($message) {
            return "您好！欢迎使用 EasyWeChat";

            // switch ($message['MsgType']) {
            //     case 'event':
            //         return '收到事件消息';
            //         break;
            //     case 'text':
            //         return '收到文字消息';
            //         break;
            //     case 'image':
            //         return '收到图片消息';
            //         break;
            //     case 'voice':
            //         return '收到语音消息';
            //         break;
            //     case 'video':
            //         return '收到视频消息';
            //         break;
            //     case 'location':
            //         return '收到坐标消息';
            //         break;
            //     case 'link':
            //         return '收到链接消息';
            //         break;
            //     case 'file':
            //         return '收到文件消息';
            //     // ... 其它消息
            //     default:
            //         return '收到其它消息';
            //         break;
            // }

            // ...
        });

        return $app->server->serve();
    }
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
        //
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
