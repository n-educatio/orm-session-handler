<?php
namespace Neducatio\ORMSessionHandlerBundle\Tests\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Neducatio\ORMSessionHandlerBundle\Command\DestroyOutdatedSessions as BaseDestroyOutdatedSessions;

/**
 * DestroyOutdatedSessions
 */
class DestroyOutdatedSessions extends BaseDestroyOutdatedSessions
{
    /**
     * For tesing purposes only
     *
     * @param InputInterface  $input  command input
     * @param OutputInterface $output command output
     */
    public function runCommand(InputInterface $input, OutputInterface $output)
    {
        $this->execute($input, $output);
    }
}
