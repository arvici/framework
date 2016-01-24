<?php
// IF NOT RUNNING PHPUNIT, EXIT DIRECTLY!!
if (! defined('PHPUNIT_RUNNING')) { exit(); }

defined('DS') || define('DS', DIRECTORY_SEPARATOR);

// Set the base path
defined('BASEPATH') || define('BASEPATH', __DIR__ . DS . 'app/');

// Require composer autoload!
require_once dirname(__DIR__) . DS . 'vendor' . DS .'autoload.php';


// Load App configuration.
$configDir = __DIR__ . DS . 'app/App/Config/';

$configFiles = scandir($configDir);

foreach ($configFiles as $fileName) {
    if (strlen($fileName) > 4 && substr($fileName, -4) === '.php') {
        require_once $configDir . $fileName;
    }
}
