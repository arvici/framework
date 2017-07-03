<?php
/**
 * App.php
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2017 Tom Valk
 */


namespace App;

use Arvici\Component\Router;
use Arvici\Heart\App\BaseApp;
use Arvici\Heart\Config\Configuration;

class App extends BaseApp
{

    /**
     * Initiate the App. This can be used to inject configuration items such as routes.
     */
    public function load()
    {
        return;
    }

    /**
     * Register the routes of the app.
     *
     * @param Router $router
     */
    public function registerRoutes(Router $router)
    {
        $router->get('/',           '\App\Controller\Welcome::index');
        $router->get('/session',    '\App\Controller\Welcome::session');
        $router->get('/json',       '\App\Controller\Welcome::json');

        $router->get('/params/double/(!int)/(!int)', '\App\Controller\TestCalled::getParameters1');

        $router->get('/exception',  '\App\Controller\Welcome::exception');
    }
}
