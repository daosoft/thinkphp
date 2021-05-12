<?php

use think\facade\Route;

const ADMIN_GUARD = 'admin';
const USER_GUARD = 'user';

/**
 * Route Rules
 */
function routeRule()
{
    Route::get(':c/:a', ':c/:a');
    Route::post(':c/:a', ':c/:aHandler');
    Route::get(':c', ':c/index');
    Route::get('/', 'Index/index');
}
