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
     * @var array
     */
    protected array $vars = [];

    /**
     * initialize
     */
    protected function initialize()
    {
        $this->app->config->set([
            'view_dir_name' => 'public/themes/default'
        ], 'view');
    }

    /**
     * @param $name
     * @param $value
     */
    protected function assign($name, $value)
    {
        $this->vars = array_merge($this->vars, [$name => $value]);
    }

    /**
     * @param string $template
     * @param array $vars
     * @return \think\response\View
     */
    protected function display(string $template = '', array $vars = []): View
    {
        if (!empty($vars)) {
            $this->vars = array_merge($this->vars, $vars);
        }

        return view('/' . $this->getTemplate($template), $this->vars);
    }

    /**
     * @param $template
     * @return string
     */
    private function getTemplate($template): string
    {
        $controller = $this->request->controller();
        $action = $this->request->action();

        if (str_contains($controller, '.')) {
            list($module, $controller) = explode('.', $controller);
            unset($module);
        }

        return empty($template) ? $controller . '_' . $action : $template;
    }
}
