<?php

namespace app\controller\web;

use app\middleware\RedirectIfAuthenticated;
use think\Request;
use think\response\Redirect;
use think\response\View;

/**
 * Class AuthController
 * @package app\controller\web
 */
class AuthController extends BaseController
{
    /**
     * @var array|string[]
     */
    protected array $middleware = [
        [RedirectIfAuthenticated::class, [USER_GUARD]],
    ];

    /**
     * @return \think\response\Redirect
     */
    public function index(): Redirect
    {
        return redirect('auth/login');
    }

    /**
     * @param \think\Request $request
     * @return \think\response\View
     */
    public function login(Request $request): View
    {
        return view('login');
    }
}
