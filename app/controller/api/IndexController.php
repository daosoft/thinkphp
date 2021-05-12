<?php

namespace app\controller\api;

use app\controller\Controller;
use think\response\Json;

/**
 * Class IndexController
 * @package app\controller\api
 */
class IndexController extends Controller
{
    /**
     * @return \think\response\Json
     */
    public function index(): Json
    {
        return $this->succeed('ok');
    }
}
