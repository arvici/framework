<?php
/**
 * App Manager
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2017 Tom Valk
 */


namespace Arvici\Heart\App;


use Arvici\Component\Router;
use Arvici\Exception\AlreadyInitiatedException;
use Arvici\Heart\Collections\DataCollection;
use Arvici\Heart\Config\Configuration;

class AppManager
{
    /**
     * List with loaded apps.
     * @var BaseApp[]
     */
    private $apps = null;

    /**
     * List with configured apps (strings).
     * @var string[]
     */
    private $appList = [];

    /**
     * Store singleton.
     * @var AppManager|null
     */
    private static $instance = null;

    /**
     * Get singleton instance of cache manager.
     * @return AppManager
     */
    public static function getInstance()
    {
        if (! self::$instance) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    /**
     * AppManager constructor.
     *
     * @codeCoverageIgnore
     */
    public function __construct()
    {
        $this->appList = Configuration::get('app.apps');
        $this->apps = new DataCollection();
    }

    /**
     * Load and initiate all apps.
     *
     * @codeCoverageIgnore
     */
    public function initApps()
    {
        if (count($this->apps) > 0) {
            throw new AlreadyInitiatedException('The apps are already loaded!');
        }

        foreach ($this->appList as $className) {
            $instance = new $className($this);
            $this->apps->append($instance);
        }

        // Let the apps configure itself.
        foreach ($this->apps as $app) {
            $app->load();
        }

        // Let the apps register it parts.
        foreach ($this->apps as $app) {
            $app->registerRoutes(Router::getInstance());
        }
    }

    /**
     * Get all initiated app instances.
     *
     * @return BaseApp[]|DataCollection
     */
    public function getApps()
    {
        return $this->apps;
    }
}
