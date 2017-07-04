<?php
/**
 * Base App class (abstract)
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2017 Tom Valk
 */


namespace Arvici\Heart\App;


use Arvici\Component\Router;
use Arvici\Heart\Config\Configuration;
use Arvici\Heart\Console\Application;

abstract class BaseApp
{
    /**
     * @var AppManager app manager.
     */
    protected $manager;

    /**
     * BaseApp constructor.
     *
     * @param AppManager $manager
     */
    public function __construct(AppManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * Initiate the App. This can be used to inject configuration items such as routes.
     */
    abstract public function load();

    /**
     * Register the routes of the app.
     *
     * @param Router $router
     */
    abstract public function registerRoutes(Router $router);

    /**
     * Get the base app directory.
     *
     * @return string
     */
    abstract public function getAppDirectory();

    /**
     * Add CLI commands to CLI tool.
     *
     * @param Application $application
     */
    public function getCommands(Application $application)
    {
        // Implement to add custom commands.
    }
}
