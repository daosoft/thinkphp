<?php

namespace app\controller\console;

use think\response\View;

/**
 * Class IndexController
 * @package app\controller\console
 */
class IndexController extends BaseController
{
    /**
     * @return \think\response\View
     */
    public function index(): View
    {
        return view('index');
    }
}
