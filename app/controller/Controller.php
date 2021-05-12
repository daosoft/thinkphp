<?php

namespace app\controller;

use app\response\JsonResponse;
use think\App;
use think\exception\ValidateException;
use think\Request;
use think\response\View;
use think\Validate;

/**
 * Class Controller
 * @package app\controller
 */
abstract class Controller
{
    use JsonResponse;

    /**
     * 应用实例
     * @var App
     */
    protected App $app;

    /**
     * Request实例
     * @var Request
     */
    protected Request $request;

    /**
     * 是否批量验证
     * @var bool
     */
    protected bool $batchValidate = false;

    /**
     * 控制器中间件
     * @var array
     */
    protected array $middleware = [];

    /**
     * @var array
     */
    protected array $vars = [];

    /**
     * 构造方法
     * @access public
     * @param App $app 应用对象
     */
    public function __construct(App $app)
    {
        $this->app = $app;
        $this->request = $this->app->request;

        // 控制器初始化
        $this->initialize();
    }

    /**
     * 初始化
     */
    protected function initialize()
    {
    }

    /**
     * 验证数据
     * @access protected
     * @param array $data 数据
     * @param string|array $validate 验证器名或者验证规则数组
     * @param array $message 提示信息
     * @param bool $batch 是否批量验证
     * @return array|string|true
     * @throws ValidateException
     */
    protected function validate(array $data, $validate, array $message = [], bool $batch = false)
    {
        if (is_array($validate)) {
            $v = new Validate();
            $v->rule($validate);
        } else {
            if (strpos($validate, '.')) {
                // 支持场景
                [$validate, $scene] = explode('.', $validate);
            }
            $class = str_contains($validate, '\\') ? $validate : $this->app->parseClass('validate', $validate);
            $v = new $class();
            if (!empty($scene)) {
                $v->scene($scene);
            }
        }

        $v->message($message);

        // 是否批量验证
        if ($batch || $this->batchValidate) {
            $v->batch(true);
        }

        return $v->failException(true)->check($data);
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
