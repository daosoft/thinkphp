<?php

namespace app\controller\home;

use think\response\View;

/**
 * Class IndexController
 * @package app\controller\home
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
