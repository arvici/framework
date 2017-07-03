<?php
/**
 * Console
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2017 Tom Valk
 */


namespace Arvici\Heart\Console;

use Arvici\Component\Asset\Commands\StaticDeployCommand;
use Arvici\Heart\Cache\Commands\CacheCommand;
use Symfony\Bridge\Monolog\Handler\ConsoleHandler;
use Symfony\Component\Console\Application as BaseApplication;

/**
 * Console Application
 *
 * @package Arvici\Heart\Console
 * @codeCoverageIgnore
 */
class Application extends BaseApplication
{
    const APP_NAME = 'Arvici Console';
    const APP_VERSION = '1.0.0';

    protected $arguments;

    public function __construct()
    {
        parent::__construct(self::APP_NAME, self::APP_VERSION);
    }

    public function prepare()
    {
        // Start Logger
        \Logger::start(true);
        \Logger::getInstance()->clearHandlers();
        \Logger::getInstance()->addHandler(
            new ConsoleHandler()
        );

        // Load commands.
        $this->prepareCore();
    }

    private function prepareCore()
    {
        $this->addCommands([
            new CacheCommand(),
            new StaticDeployCommand(),
        ]);
    }
}
