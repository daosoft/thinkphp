<?php

use think\facade\Route;

/**
 * Route Rules
 */
function routeRule()
{
    Route::get(':c/:a', ':c/:a');
    Route::post(':c/:a', ':c/:aHandler')->token();
    Route::get(':c', ':c/index');
    Route::get('/', 'Index/index');
}
