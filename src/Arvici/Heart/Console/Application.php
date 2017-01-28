<?php
/**
 * Console
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2017 Tom Valk
 */


namespace Arvici\Heart\Console;

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

    }

    public function dispatch($arguments)
    {
        $this->arguments = $arguments;


    }
}
