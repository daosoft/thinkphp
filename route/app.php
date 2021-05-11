<?php

use think\facade\Route;

foreach (glob(app_path('controller/*')) as $item) {
    $module = basename($item);

    if ($module === config('route.default_route')) {
        continue;
    }

    $prefix = config('route.route_mapper')[$module] ?? $module;

    Route::group($prefix, function () {
        return routeRule();
    })->prefix($module . '.');
}

Route::group(function () {
    return routeRule();
})->prefix(config('route.default_route') . '.');
