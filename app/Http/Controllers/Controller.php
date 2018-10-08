<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function error($message = 'oh~ 出错了！', $code = 5011)
    {
        return response()->json([
            'res'     => false,
            'code'    => $code,
            'message' => $message
        ]);
    }

    public function success($data = [], $message = '提交成功')
    {
        return response()->json([
            'res'     => true,
            'code'    => 200,
            'data'    => $data,
            'message' => $message
        ]);
    }
}
