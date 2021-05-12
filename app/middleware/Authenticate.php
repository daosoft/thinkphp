<?php

namespace app\middleware;

use Closure;
use think\Request;

/**
 * Class Authenticate
 * @package app\middleware
 */
class Authenticate
{
    /**
     * 处理请求
     *
     * @param \think\Request $request
     * @param \Closure $next
     * @param string $guard
     * @return Response
     */
    public function handle(Request $request, Closure $next, string $guard)
    {
        if (!session('?auth_' . $guard)) {
            return redirect($guard . '/auth/login?callback=' . urlencode($request->url(true)));
        }

        return $next($request);
    }
}
