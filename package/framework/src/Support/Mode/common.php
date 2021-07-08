<?php

return array(
    // 配置文件
    'config' => array(
        CORE_PATH . 'Conf/convention.php', // 系统惯例配置
        CONF_PATH . 'config' . CONF_EXT, // 应用公共配置
    ),

    // 函数和类文件
    'core' => array(
        CORE_PATH . 'Support/helpers.php',
        COMMON_PATH . 'Common/function.php',
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
