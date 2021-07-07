<?php

return array(
    // 配置文件
    'config' => array(
        CORE_PATH . 'Conf/convention.php', // 系统惯例配置
        CONF_PATH . 'config' . CONF_EXT, // 应用公共配置
    ),

    // 别名定义
    'alias' => array(
        'Think\Log' => CORE_PATH . 'Log' . EXT,
        'Think\Log\Driver\File' => CORE_PATH . 'Log/Driver/File' . EXT,
        'Think\Exception' => CORE_PATH . 'Exception' . EXT,
        'Think\Model' => CORE_PATH . 'Model' . EXT,
        'Think\Db' => CORE_PATH . 'Db' . EXT,
        'Think\Template' => CORE_PATH . 'Template' . EXT,
        'Think\Cache' => CORE_PATH . 'Cache' . EXT,
        'Think\Cache\Driver\File' => CORE_PATH . 'Cache/Driver/File' . EXT,
        'Think\Storage' => CORE_PATH . 'Storage' . EXT,
    ),

    // 函数和类文件
    'core' => array(
        CORE_PATH . 'Support/helpers.php',
        COMMON_PATH . 'Common/function.php',
        CORE_PATH . 'Hook' . EXT,
        CORE_PATH . 'App' . EXT,
        CORE_PATH . 'Dispatcher' . EXT,
        CORE_PATH . 'Route' . EXT,
        CORE_PATH . 'Controller' . EXT,
        CORE_PATH . 'View' . EXT,
        CORE_PATH . 'Behavior/ParseTemplateBehavior' . EXT,
        CORE_PATH . 'Behavior/ContentReplaceBehavior' . EXT,
    ),
    // 行为扩展定义
    'tags' => array(
        'app_init' => array(),
        'app_begin' => array(
            'Think\Behavior\ReadHtmlCacheBehavior', // 读取静态缓存
        ),
        'app_end' => array(
            'Think\Behavior\ShowPageTraceBehavior', // 页面Trace显示
        ),
        'view_parse' => array(
            'Think\Behavior\ParseTemplateBehavior', // 模板解析 支持PHP、内置模板引擎和第三方模板引擎
        ),
        'template_filter' => array(
            'Think\Behavior\ContentReplaceBehavior', // 模板输出替换
        ),
        'view_filter' => array(
            'Think\Behavior\WriteHtmlCacheBehavior', // 写入静态缓存
        ),
    ),
);
