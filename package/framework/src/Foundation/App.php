<?php

namespace Think\Foundation;

use Think\Hook;
use Think\Log;
use Think\Storage;

/**
 * Class App
 * @package Think\Foundation
 */
class App
{
    // 实例化对象
    private static $_instance = array();

    /**
     * 应用程序初始化
     * @access public
     * @return void
     */
    public static function init()
    {
        // 日志目录转换为绝对路径 默认情况下存储到公共模块下面
        C('LOG_PATH', realpath(LOG_PATH) . '/Common/');

        // 定义当前请求的系统常量
        define('NOW_TIME', $_SERVER['REQUEST_TIME']);
        define('REQUEST_METHOD', $_SERVER['REQUEST_METHOD']);
        define('IS_GET', REQUEST_METHOD == 'GET' ? true : false);
        define('IS_POST', REQUEST_METHOD == 'POST' ? true : false);
        define('IS_PUT', REQUEST_METHOD == 'PUT' ? true : false);
        define('IS_DELETE', REQUEST_METHOD == 'DELETE' ? true : false);

        // URL调度
        Dispatcher::dispatch();

        if (C('REQUEST_VARS_FILTER')) {
            // 全局安全过滤
            array_walk_recursive($_GET, 'think_filter');
            array_walk_recursive($_POST, 'think_filter');
            array_walk_recursive($_REQUEST, 'think_filter');
        }

        // URL调度结束标签
        Hook::listen('url_dispatch');

        define('IS_AJAX', ((isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') || !empty($_POST[C('VAR_AJAX_SUBMIT')]) || !empty($_GET[C('VAR_AJAX_SUBMIT')])) ? true : false);

        // TMPL_EXCEPTION_FILE 改为绝对地址
        C('TMPL_EXCEPTION_FILE', realpath(C('TMPL_EXCEPTION_FILE')));
    }

    /**
     * 执行应用程序
     * @access public
     * @return void
     */
    public static function exec()
    {
        if (!preg_match('/^[A-Za-z](\/|\w)*$/', CONTROLLER_NAME)) {
            // 安全检测
            $module = false;
        } elseif (C('ACTION_BIND_CLASS')) {
            // 操作绑定到类：模块\Controller\控制器\操作
            $layer = C('DEFAULT_C_LAYER');
            if (is_dir(MODULE_PATH . $layer . '/' . CONTROLLER_NAME)) {
                $namespace = MODULE_NAME . '\\' . $layer . '\\' . CONTROLLER_NAME . '\\';
            } else {
                // 空控制器
                $namespace = MODULE_NAME . '\\' . $layer . '\\_empty\\';
            }
            $actionName = strtolower(ACTION_NAME);
            if (class_exists($namespace . $actionName)) {
                $class = $namespace . $actionName;
            } elseif (class_exists($namespace . '_empty')) {
                // 空操作
                $class = $namespace . '_empty';
            } else {
                E(L('_ERROR_ACTION_') . ':' . ACTION_NAME);
            }
            $module = new $class;
            // 操作绑定到类后 固定执行run入口
            $action = 'run';
        } else {
            //创建控制器实例
            $module = controller(CONTROLLER_NAME, CONTROLLER_PATH);
        }

        if (!$module) {
            // 是否定义Empty控制器
            $module = A('Empty');
            if (!$module) {
                E(L('_CONTROLLER_NOT_EXIST_') . ':' . CONTROLLER_NAME);
            }
        }

        // 获取当前操作名 支持动态路由
        if (!isset($action)) {
            $action = ACTION_NAME . C('ACTION_SUFFIX');
        }
        try {
            self::invokeAction($module, $action);
        } catch (\ReflectionException $e) {
            // 方法调用发生异常后 引导到__call方法处理
            $method = new \ReflectionMethod($module, '__call');
            $method->invokeArgs($module, array($action, ''));
        }
    }

    public static function invokeAction($module, $action)
    {
        if (!preg_match('/^[A-Za-z](\w)*$/', $action)) {
            // 非法操作
            throw new \ReflectionException();
        }
        //执行当前操作
        $method = new \ReflectionMethod($module, $action);
        if ($method->isPublic() && !$method->isStatic()) {
            $class = new \ReflectionClass($module);
            // 前置操作
            if ($class->hasMethod('_before_' . $action)) {
                $before = $class->getMethod('_before_' . $action);
                if ($before->isPublic()) {
                    $before->invoke($module);
                }
            }
            // URL参数绑定检测
            if ($method->getNumberOfParameters() > 0 && C('URL_PARAMS_BIND')) {
                switch ($_SERVER['REQUEST_METHOD']) {
                    case 'POST':
                        $vars = array_merge($_GET, $_POST);
                        break;
                    case 'PUT':
                        parse_str(file_get_contents('php://input'), $vars);
                        break;
                    default:
                        $vars = $_GET;
                }
                $params = $method->getParameters();
                $paramsBindType = C('URL_PARAMS_BIND_TYPE');
                foreach ($params as $param) {
                    $name = $param->getName();
                    if (1 == $paramsBindType && !empty($vars)) {
                        $args[] = array_shift($vars);
                    } elseif (0 == $paramsBindType && isset($vars[$name])) {
                        $args[] = $vars[$name];
                    } elseif ($param->isDefaultValueAvailable()) {
                        $args[] = $param->getDefaultValue();
                    } else {
                        E(L('_PARAM_ERROR_') . ':' . $name);
                    }
                }
                // 开启绑定参数过滤机制
                if (C('URL_PARAMS_FILTER')) {
                    $filters = C('URL_PARAMS_FILTER_TYPE') ?: C('DEFAULT_FILTER');
                    if ($filters) {
                        $filters = explode(',', $filters);
                        foreach ($filters as $filter) {
                            $args = array_map_recursive($filter, $args); // 参数过滤
                        }
                    }
                }
                array_walk_recursive($args, 'think_filter');
                $method->invokeArgs($module, $args);
            } else {
                $method->invoke($module);
            }
            // 后置操作
            if ($class->hasMethod('_after_' . $action)) {
                $after = $class->getMethod('_after_' . $action);
                if ($after->isPublic()) {
                    $after->invoke($module);
                }
            }
        } else {
            // 操作方法不是Public 抛出异常
            throw new \ReflectionException();
        }
    }

    /**
     * 运行应用实例 入口文件使用的快捷方法
     * @access public
     * @return void
     */
    public static function run()
    {
        // 加载动态应用公共文件和配置
        load_ext_file(COMMON_PATH);
        // 应用初始化标签
        Hook::listen('app_init');
        App::init();
        // 应用开始标签
        Hook::listen('app_begin');
        // Session初始化
        if (!IS_CLI) {
            session(C('SESSION_OPTIONS'));
        }
        // 记录应用初始化时间
        G('initTime');
        App::exec();
        // 应用结束标签
        Hook::listen('app_end');
    }

    // ----

    /**
     * 应用程序初始化
     * @access public
     * @return void
     */
    public static function start()
    {
        define('CORE_PATH', dirname(__DIR__) . '/');

        // 设定错误和异常处理
        $whoops = new \Whoops\Run;
        $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
        $whoops->register();

        // 初始化文件存储方式
        Storage::connect(STORAGE_TYPE);

        // 读取应用模式
        $mode = include is_file(CONF_PATH . 'core.php') ? CONF_PATH . 'core.php' : MODE_PATH . APP_MODE . '.php';
        // 加载核心文件
        foreach ($mode['core'] as $file) {
            if (is_file($file)) {
                include $file;
            }
        }

        // 加载应用模式配置文件
        foreach ($mode['config'] as $key => $file) {
            is_numeric($key) ? C(load_config($file)) : C($key, load_config($file));
        }

        // 读取当前应用模式对应的配置文件
        if ('common' != APP_MODE && is_file(CONF_PATH . 'config_' . APP_MODE . CONF_EXT)) {
            C(load_config(CONF_PATH . 'config_' . APP_MODE . CONF_EXT));
        }

        // 加载模式行为定义
        if (isset($mode['tags'])) {
            Hook::import(is_array($mode['tags']) ? $mode['tags'] : include $mode['tags']);
        }

        // 加载应用行为定义
        if (is_file(CONF_PATH . 'tags.php')) // 允许应用增加开发模式配置定义
        {
            Hook::import(include CONF_PATH . 'tags.php');
        }

        // 加载框架底层语言包
        L(include THINK_PATH . 'Lang/' . strtolower(C('DEFAULT_LANG')) . '.php');

        if (APP_DEBUG) {
            // 调试模式加载系统默认的配置文件
            C(include THINK_PATH . 'Conf/debug.php');
            // 读取应用调试配置文件
            if (is_file(CONF_PATH . 'debug' . CONF_EXT)) {
                C(include CONF_PATH . 'debug' . CONF_EXT);
            }
        }

        // 设置系统时区
        date_default_timezone_set(C('DEFAULT_TIMEZONE'));

        // 记录加载文件时间
        G('loadTime');
        // 运行应用
        App::run();
    }

    /**
     * 取得对象实例 支持调用类的静态方法
     * @param string $class 对象类名
     * @param string $method 类的静态方法名
     * @return object
     */
    public static function instance($class, $method = '')
    {
        $identify = $class . $method;
        if (!isset(self::$_instance[$identify])) {
            if (class_exists($class)) {
                $o = new $class();
                if (!empty($method) && method_exists($o, $method)) {
                    self::$_instance[$identify] = call_user_func(array(&$o, $method));
                } else {
                    self::$_instance[$identify] = $o;
                }
            } else {
                self::halt(L('_CLASS_NOT_EXIST_') . ':' . $class);
            }

        }
        return self::$_instance[$identify];
    }

    /**
     * 添加和获取页面Trace记录
     * @param string $value 变量
     * @param string $label 标签
     * @param string $level 日志级别(或者页面Trace的选项卡)
     * @param boolean $record 是否记录日志
     * @return void|array
     */
    public static function trace($value = '[think]', $label = '', $level = 'DEBUG', $record = false)
    {
        static $_trace = array();
        if ('[think]' === $value) {
            // 获取trace信息
            return $_trace;
        } else {
            $info = ($label ? $label . ':' : '') . print_r($value, true);
            $level = strtoupper($level);

            if ((defined('IS_AJAX') && IS_AJAX) || !C('SHOW_PAGE_TRACE') || $record) {
                Log::record($info, $level, $record);
            } else {
                if (!isset($_trace[$level]) || count($_trace[$level]) > C('TRACE_MAX_RECORD')) {
                    $_trace[$level] = array();
                }
                $_trace[$level][] = $info;
            }
        }
    }
}
