<?php
/**
 * Second App
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2017 Tom Valk
 */

namespace SecondApp;

use Arvici\Component\Router;
use Arvici\Heart\App\BaseApp;


class SecondApp extends BaseApp
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
        $router->get('/test2', '\SecondApp\Controller\Welcome');
    }

    /**
     * Get the base app directory.
     *
     * @return string
     */
    public function getAppDirectory()
    {
        return dirname(__FILE__);
    }
}
