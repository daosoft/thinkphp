<?php

namespace app\controller\web;

use app\controller\Controller;
use think\response\View;

/**
 * Class BaseController
 * @package app\controller\web
 */
class BaseController extends Controller
{
    /**
     * initialize
     */
    protected function initialize()
    {
        $this->app->config->set([
            'view_dir_name' => 'public/themes/default'
        ], 'view');
    }

}
