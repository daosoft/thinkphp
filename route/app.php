<?php

use think\facade\Route;

foreach (config('route.route_mapper') as $module => $prefix) {
    Route::group($prefix, function () {
        routeRule();
    })->prefix($module . '.');
}
