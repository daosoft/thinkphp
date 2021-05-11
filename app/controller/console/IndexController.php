<?php

namespace app\controller\console;

use app\controller\Controller;

/**
 * Class IndexController
 * @package app\controller\console
 */
class IndexController extends Controller
{
    public function index()
    {
        return 'hello console';
    }
}
