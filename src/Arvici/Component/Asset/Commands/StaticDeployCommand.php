<?php
/**
 * Static file deployment command
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2017 Tom Valk
 */


namespace Arvici\Component\Asset\Commands;

use Arvici\Component\Console\Command;
use Arvici\Exception\AlreadyInitiatedException;
use Arvici\Heart\App\AppManager;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;


/**
 * Class StaticDeployCommand
 * @package Arvici\Heart\Cache\Commands
 *
 * @codeCoverageIgnore
 */
class StaticDeployCommand extends Command
{
    protected function configure()
    {
        $this->setName('static:deploy')
            ->setDescription('Deploy the static files of the apps for production.')
            ->setHelp('This command will deploy all static files of the Apps to the public/assets folder.');
    }

    private function emptyDirectory($directory)
    {
        if (is_file($directory)) {
            return @unlink($directory);
        }
        elseif (is_dir($directory))
        {
            $scan = glob(rtrim($directory, '/') . '/*');
            foreach ($scan as $index => $path) {
                $this->emptyDirectory($path);
            }
        }
        return false;
    }

    private function recurseCopy($src,$dst) {
        $dir = opendir($src);
        @mkdir($dst);
        while(false !== ( $file = readdir($dir)) ) {
            if (( $file != '.' ) && ( $file != '..' )) {
                if ( is_dir($src . '/' . $file) ) {
                    $this->recurseCopy($src . '/' . $file,$dst . '/' . $file);
                }
                else {
                    copy($src . '/' . $file,$dst . '/' . $file);
                }
            }
        }
        closedir($dir);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $io->writeln('<info>Clearing current files...</info>');
        $assetsPublicDirectory = BASEPATH . 'public' . DS . 'assets';
        $this->emptyDirectory($assetsPublicDirectory);
        @mkdir($assetsPublicDirectory);

        $io->writeln('<info>Getting apps...</info>');

        $appDirectories = [];
        try {
            AppManager::getInstance()->initApps();
        } catch (AlreadyInitiatedException $exception) {
            // Ignore.
        }

        foreach (AppManager::getInstance()->getApps() as $app) {
            $appDirectories[] = $app->getAppDirectory();
        }

        $io->writeln('<info>Deploying assets...</info>');
        foreach ($appDirectories as $directory) {
            $io->write(' |- <info>Deploying</info> ' . $directory);
            if (is_dir($directory . DS . 'Assets')) {
                $io->write(' ... ');
                $this->recurseCopy($directory . DS . 'Assets' . DS, $assetsPublicDirectory);
                $io->write('<info>Done</info>');
            }
            $io->writeln('');
        }

        $io->success('Files deployed!');
    }
}
