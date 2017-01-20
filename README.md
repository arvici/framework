# Arvici Framework - Framework Core Package
[![Build Status](https://travis-ci.org/arvici/framework.svg)](https://travis-ci.org/arvici/framework)
[![License](https://poser.pugx.org/arvici/framework/license)](https://packagist.org/packages/arvici/framework)
[![Coverage Status](https://coveralls.io/repos/arvici/framework/badge.svg?branch=master&service=github)](https://coveralls.io/github/arvici/framework?branch=master)
[![Codacy Badge](https://api.codacy.com/project/badge/coverage/21d1532f2e334bacb086711de2eb1798)](https://www.codacy.com/app/tomvalk/arvici-framework)
[![Codacy Badge](https://api.codacy.com/project/badge/grade/21d1532f2e334bacb086711de2eb1798)](https://www.codacy.com/app/tomvalk/arvici-framework)
[![Build Time](https://buildtimetrend.herokuapp.com/badge/arvici/framework)](https://buildtimetrend.herokuapp.com/dashboard/arvici/framework)

[![Latest Stable Version](https://poser.pugx.org/arvici/framework/v/stable)](https://packagist.org/packages/arvici/framework)
[![Latest Unstable Version](https://poser.pugx.org/arvici/framework/v/unstable)](https://packagist.org/packages/arvici/framework)
[![Dependency Status](https://www.versioneye.com/user/projects/5698e4f3af789b0027001ee2/badge.svg?style=flat)](https://www.versioneye.com/user/projects/5698e4f3af789b0027001ee2)

Arvici Framework


# Introduction

This package contains the core functionality of the Arvici Framework. Currently being in development!


# Getting started

Using this package is for experts only, please follow the link bellow to get instructions on how to get started with the framework.
[Getting started, use composer create-project](https://github.com/arvici/arvici#arvici-framework---start-project)


# Components

## Router
You can define your routes in the Router.php configuration. Define your routes with
the Router::define method.

Example:
```php
Router::define(function(Router $router) {
    $router->get('/',           '\App\Controller\Welcome::index');
    $router->get('/session',    '\App\Controller\Welcome::session');
    $router->get('/json',       '\App\Controller\Welcome::json');
    $router->get('/exception',  '\App\Controller\Welcome::exception');
});
```

## Database
Configuration of your database is located in the Database.php.

Example:
```php
Configuration::define('database', function() {
    return [
        /**
         * The default fetch type to use.
         */
        'fetchType' => \Arvici\Heart\Database\Database::FETCH_ASSOC,
        
        /**
         * Database Connection names and configuration values.
         *
         * 'default' is used when no connection name is provided, or using SweetORM.
         */
        'connections' => [
            'default' => [
                'driver'        => 'MySQL',
                'host'          => 'localhost',
                'username'      => 'root',
                'password'      => '',
                'port'          => 3306,
                'database'      => 'arvici_test'
            ],
        ]
    ];
});
```

## Models/ORM
When using the ORM, check the separate documentation: https://github.com/tomvlk/sweet-orm#defining-entities

## Caching
To use the Caching system, you have to define the Caching configuration or use the FileSystem by default.

Configuration file `Cache.php`:
```php
Configure::define('cache', function () {
    return [

        /**
         * Enable cache.
         */
        'enabled' => true,

        /**
         * Set to true to enable cache even in non-production mode.
         */
        'forceEnabled' => true,

        /**
         * Cache Pool Configuration.
         */
        'pools' => [
            'default' => [
                'driver' => '\Stash\Driver\FileSystem',
                'options' => [
                    'path' => BASEPATH . 'cache' . DS,
                ]
            ],
        ],

    ];
});
```


### Using the cache pools
To retrieve a pool (where you can save and get items) you have to use the Manager:
```php
$manager = \Arvici\Component\Cache::getInstance();
```

In the next step you need to get the Pool. The pool is configured in your configuration file.
```php
$pool = $manager->getPool(); // pool name = 'default'
// or with a pool name:
$pool = $manager->getPool('redis-cache'); // pool name = 'redis-cache'
```

To retrieve, save or use an item you first have to get the context. 
With the instance of Item you can read and manipulate the content.

Examples of usage:
```php
$item = $pool->get('test/cachingkey');
$item->set($data);

$expiration = new DateTime('2020-01-21');
$item->expiresAfter($expiration);

$item->save();

$data = $item->get();

$item->isMiss(); // bool

```

### More information
The caching library that is used is Stash. For more information on using the pools see: http://www.stashphp.com/Basics.html

# License

MIT License, see LICENSE file.
