<?php

namespace app\controller\home;

use app\controller\Controller;
use app\middleware\Authenticate;

/**
 * Class BaseController
 * @package app\controller\home
 */
class BaseController extends Controller
{
    /**
     * @var array|string[]
     */
    protected array $middleware = [
        [Authenticate::class, [USER_PATH]],
    ];
}
