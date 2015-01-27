<?php

use Cake\Routing\Router;

Router::prefix('admin', function ($routes) {
    $routes->plugin('WhosOnline', ['path' => '/whosonline'], function ($routes) {
        $routes->fallbacks();
    });
});
