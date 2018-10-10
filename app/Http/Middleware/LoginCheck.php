<?php

namespace App\Http\Middleware;

use Closure;

class LoginCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = auth()->user();
        $uri  = $request->getRequestUri();
        if($request->ajax() || $request->wantsJson()){
            if(!$user && $uri != '/admin/login'){
             return response()->json([
                    'res'     => false,
                    'code'    => 5011,
                    'message' => '非法请求'
                ]);
            }
        }else{
            if(!$user && $uri != '/view/login'){
                return redirect('/view/login');
            }
            if($user && $uri == '/view/login'){
                return redirect('/view/product_exchange');
            }
        }

        return $next($request);
    }
}
