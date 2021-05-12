<?php

namespace app\controller\console;

use app\controller\Controller;
use app\middleware\Authenticate;

/**
 * Class BaseController
 * @package app\controller\console
 */
class BaseController extends Controller
{
    /**
     * @var array|string[]
     */
    protected array $middleware = [
        [Authenticate::class, ['admin']],
    ];
}
