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
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        return $next($request);
    }
}
