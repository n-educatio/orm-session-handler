<?php
namespace Neducatio\ORMSessionHandlerBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Neducatio\ORMSessionHandlerBundle\Repository\SessionRepository;

/**
 * DestroyOutdatedSessions command is used to destroy sessions that are regarded as garbage after specified time (session.gc_maxlifetime) of inactivity
 */
class DestroyOutdatedSessions extends Command
{
    /**
     * @var SessionRepository
     */
    private $sessionRepository;

    /**
     * http://php.net/manual/en/session.configuration.php#ini.session.gc-maxlifetime
     *
     * @var int
     */
    private $maxlifetime;

    /**
     * Constructor
     *
     * @param SessionRepository $sessionRepository
     * @param int               $maxlifetime
     */
    public function __construct(SessionRepository $sessionRepository, $maxlifetime)
    {
        $this->sessionRepository = $sessionRepository;
        $this->maxlifetime = $maxlifetime;

        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('neducatio:orm-session-handler:destroy-outdated-sessions')
            ->setDescription('Destroy sessions that are regarded as garbage after specified time (session.gc_maxlifetime) of inactivity');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->sessionRepository->removeOutdated($this->maxlifetime);
    }
}