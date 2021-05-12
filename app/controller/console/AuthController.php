<?php

namespace app\controller\console;

use app\controller\Controller;
use app\middleware\RedirectIfAuthenticated;
use think\Request;
use think\response\Json;
use think\response\Redirect;
use think\response\View;

/**
 * Class AuthController
 * @package app\controller\console
 */
class AuthController extends Controller
{
    /**
     * @var array|string[]
     */
    protected array $middleware = [
        [RedirectIfAuthenticated::class, [ADMIN_PATH]],
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

    /**
     * @param \think\Request $request
     * @return \think\response\Json
     */
    public function loginHandler(Request $request): Json
    {
        return $this->succeed('admin login');
    }
}
