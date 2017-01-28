<?php
/**
 * CacheCommand
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2017 Tom Valk
 */


namespace Arvici\Heart\Cache\Commands;

use Arvici\Component\Cache;
use Arvici\Component\Console\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;


class CacheCommand extends Command
{
    protected function configure()
    {
        $this->setName('cache:clear')
            ->setDescription('Clear the configured cache backend\'s')
            ->setHelp('This command clears the stored cache in the configured backend\'s.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $io->writeln('<info>Clearing cache...</info>');

        // Loop over all pools.
        foreach (Cache::getInstance()->getPools() as $pool) {
            $io->write('Clearing <info>' . $pool->getName() . '</info>... ');

            $pool->clear();
            usleep(500000); // We will sleep to prevent clearing too fast and reaching IO limits.
                            // For some backend's it's quite CPU/Memory intensive to clear, if there are two
                            // pools defined for the same backend it's more likely you want to sleep.

            $io->writeln('<info>Done</info>');
        }

        $io->success('Cache cleared!');
    }
}
