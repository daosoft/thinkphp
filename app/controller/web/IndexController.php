<?php

namespace app\controller\web;

use think\response\View;

/**
 * Class IndexController
 * @package app\controller\web
 */
class IndexController extends BaseController
{
    /**
     * @return \think\response\View
     */
    public function index(): View
    {
        return $this->display('index');
    }
}
