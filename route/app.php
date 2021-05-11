<?php

use think\facade\Route;

foreach (['api' => 'api', 'console' => 'admin'] as $m => $p) {
    Route::group($p, function () {
        return routeRule();
    })->prefix($m . '.');
}

Route::group(function () {
    return routeRule();
})->prefix('web.');
