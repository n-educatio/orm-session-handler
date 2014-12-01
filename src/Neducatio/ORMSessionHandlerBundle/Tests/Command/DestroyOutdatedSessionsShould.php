<?php
namespace Neducatio\ORMSessionHandlerBundle\Tests\Command;

use Symfony\Component\Console\Output\OutputInterface;
use Mockery as m;

/**
 * Unit tests for Neducatio\ORMSessionHandlerBundle\Command\DestroyOutdatedSessions
 *
 * @covers Neducatio\ORMSessionHandlerBundle\Command\DestroyOutdatedSessions
 */
class DestroyOutdatedSessionsShould extends \PHPUnit_Framework_TestCase
{
    const SESSION_MAXLIFETIME = 50;

    /**
     * @var DestroyOutdatedSessionsShould
     */
    private $commandDestroyOutdatedSessions;

    /**
     * \Neducatio\ORMSessionHandlerBundle\Repository\SessionRepository Mock
     */
    private $sessionRepository;

    /**
     * DestroyOutdatedSessions::__constructor
     *  should
     *    construct new instance
     *  when
     *    valid dependencies are provided
     *
     * @test
     */
    public function beInstanceOfDestroyOutdatedSessions()
    {
        $this->assertInstanceOf('Neducatio\ORMSessionHandlerBundle\Command\DestroyOutdatedSessions', $this->commandDestroyOutdatedSessions);
    }

    /**
     * DestroyOutdatedSessions::execute
     *  should
     *    destroy outdated sessions
     *
     * @test
     */
    public function destroyOutdatedSessions()
    {
        $this->sessionRepositoryExpectsRemoveOutdatedMethodCall();

        list($input, $output) = $this->prepareCommandParams();

        $this->commandDestroyOutdatedSessions->runCommand($input, $output);
    }

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->sessionRepository = m::mock('Neducatio\ORMSessionHandlerBundle\Repository\SessionRepository');
        $this->commandDestroyOutdatedSessions = new DestroyOutdatedSessions($this->sessionRepository, self::SESSION_MAXLIFETIME);
    }

    /**
     * {@inheritdoc}
     */
    public function tearDown()
    {
        m::close();
    }

    private function sessionRepositoryExpectsRemoveOutdatedMethodCall()
    {
        $this->sessionRepository->shouldReceive('removeOutdated')->with(self::SESSION_MAXLIFETIME)->once();
    }

    private function prepareCommandParams()
    {
        $input = m::mock('Symfony\Component\Console\Input\InputInterface');
        $output = m::mock('Symfony\Component\Console\Output\OutputInterface');
        $output->shouldReceive('getVerbosity')->andReturn(OutputInterface::VERBOSITY_NORMAL);

        return [$input, $output];
    }

}
