<?php
/**
 * Console
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2017 Tom Valk
 */


namespace Arvici\Heart\Console;

use Arvici\Component\Asset\Commands\StaticDeployCommand;
use Arvici\Heart\App\AppManager;
use Arvici\Heart\Cache\Commands\CacheCommand;
use Arvici\Heart\Database\Database;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper;
use Symfony\Bridge\Monolog\Handler\ConsoleHandler;
use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Helper\QuestionHelper;

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

        // Initiate apps.
        try {
            AppManager::getInstance()->initApps();
        } catch (\Exception $exception) {
            // ignore
        }

        // Initiate the Doctrine commands.
        try {
            $db = Database::connection();
            $db = $db->getDbalConnection();
            $em = Database::entityManager();

            $this->getHelperSet()->set(new ConnectionHelper($db));
            $this->getHelperSet()->set(new EntityManagerHelper($em));
            $this->getHelperSet()->set(new EntityManagerHelper($em), 'em');
            $this->getHelperSet()->set(new QuestionHelper());
        } catch (\Exception $exception) {
            echo($exception);
            // ignore
        }

        // Load commands.
        $this->prepareCore();

        // Load app commands.
        foreach (AppManager::getInstance()->getApps() as $app) {
            $app->getCommands($this);
        }
    }

    private function prepareCore()
    {
        $this->addCommands([
            new CacheCommand(),
            new StaticDeployCommand(),

            // Doctrine DBAL.
            new \Doctrine\DBAL\Tools\Console\Command\ImportCommand(),

            // Doctrine Migrations.
            new \Doctrine\DBAL\Migrations\Tools\Console\Command\DiffCommand(),
            new \Doctrine\DBAL\Migrations\Tools\Console\Command\ExecuteCommand(),
            new \Doctrine\DBAL\Migrations\Tools\Console\Command\GenerateCommand(),
            new \Doctrine\DBAL\Migrations\Tools\Console\Command\MigrateCommand(),
            new \Doctrine\DBAL\Migrations\Tools\Console\Command\StatusCommand(),
            new \Doctrine\DBAL\Migrations\Tools\Console\Command\VersionCommand(),
            new \Doctrine\DBAL\Migrations\Tools\Console\Command\LatestCommand(),
            new \Doctrine\DBAL\Migrations\Tools\Console\Command\UpToDateCommand(),

            // Doctrine ORM.
            new \Doctrine\ORM\Tools\Console\Command\ClearCache\MetadataCommand(),
            new \Doctrine\ORM\Tools\Console\Command\ClearCache\ResultCommand(),
            new \Doctrine\ORM\Tools\Console\Command\ClearCache\QueryCommand(),
            new \Doctrine\ORM\Tools\Console\Command\SchemaTool\CreateCommand(),
            new \Doctrine\ORM\Tools\Console\Command\SchemaTool\UpdateCommand(),
            new \Doctrine\ORM\Tools\Console\Command\SchemaTool\DropCommand(),
            new \Doctrine\ORM\Tools\Console\Command\EnsureProductionSettingsCommand(),
            new \Doctrine\ORM\Tools\Console\Command\ConvertDoctrine1SchemaCommand(),
            new \Doctrine\ORM\Tools\Console\Command\GenerateRepositoriesCommand(),
            new \Doctrine\ORM\Tools\Console\Command\GenerateEntitiesCommand(),
            new \Doctrine\ORM\Tools\Console\Command\GenerateProxiesCommand(),
            new \Doctrine\ORM\Tools\Console\Command\ConvertMappingCommand(),
            new \Doctrine\ORM\Tools\Console\Command\RunDqlCommand(),
            new \Doctrine\ORM\Tools\Console\Command\ValidateSchemaCommand(),
            new \Doctrine\ORM\Tools\Console\Command\InfoCommand(),
            new \Doctrine\ORM\Tools\Console\Command\MappingDescribeCommand(),
        ]);
    }
}
