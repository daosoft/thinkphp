<?php

namespace app\middleware;

use Closure;
use think\Request;

/**
 * Class RedirectIfAuthenticated
 * @package app\middleware
 */
class RedirectIfAuthenticated
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
        if (session('?auth_' . $guard)) {
            return redirect('/' . $guard);
        }

        return $next($request);
    }
}
