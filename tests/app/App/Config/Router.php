<?php

use \Arvici\Component\Router;

/**
 * Router Configuration
 */
Router::define(function(Router $router) {
    $router->get('/',           '\App\Controller\Welcome::index');
    $router->get('/session',    '\App\Controller\Welcome::session');
    $router->get('/json',       '\App\Controller\Welcome::json');

    $router->get('/exception',  '\App\Controller\Welcome::exception');
});

