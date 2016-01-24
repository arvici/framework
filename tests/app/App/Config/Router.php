<?php

use \Arvici\Component\Router;

/**
 * Router Configuration
 */
Router::define(function(Router $router) {
    $router->get('/', '\App\Controller\Welcome::index');
    $router->get('/json', '\App\Controller\Welcome::json');
});

