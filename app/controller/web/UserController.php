<?php

namespace app\controller\web;

use app\middleware\Authenticate;
use think\response\View;

/**
 * Class UserController
 * @package app\controller\web
 */
class UserController extends BaseController
{
    /**
     * @var array|string[]
     */
    protected array $middleware = [
        [Authenticate::class, [USER_PATH]],
    ];

    /**
     * @return \think\response\View
     */
    public function index(): View
    {
        return $this->display('index');
    }
}
