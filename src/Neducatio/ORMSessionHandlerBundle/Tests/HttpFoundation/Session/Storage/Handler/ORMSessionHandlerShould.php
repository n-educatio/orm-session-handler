<?php
namespace Neducatio\ORMSessionHandlerBundle\Tests\HttpFoundation\Session\Storage\Handler;

use Neducatio\ORMSessionHandlerBundle\HttpFoundation\Session\Storage\Handler\ORMSessionHandler;
use Neducatio\ORMSessionHandlerBundle\Tests\Model\Session;
use Mockery as m;

/**
 * Unit tests for Neducatio\ORMSessionHandlerBundle\HttpFoundation\Session\Storage\Handler\ORMSessionHandler
 *
 * @covers Neducatio\ORMSessionHandlerBundle\HttpFoundation\Session\Storage\Handler\ORMSessionHandler
 */
class ORMSessionHandlerShould extends \PHPUnit_Framework_TestCase
{
    const SESSION_PATH = '/session/path';
    const SESSION_ID = 'e1k2w3a4d5o6r7';
    const SESSION_NAME = 'session_name';
    const SESSION_MAXLIFETIME = 60;

    /**
     * @var ORMSessionHandler
     */
    private $ORMSessionHandler;

    /**
     * Mock of Neducatio\ORMSessionHandlerBundle\Repository\SessionRepository
     */
    private $sessionRepository;

    /**
     * Mock of Neducatio\ORMSessionHandlerBundle\HttpFoundation\Session\Storage\Handler\SessionWriter
     */
    private $sessionWriter;

    /**
     * @var array
     */
    private $sessionData = ['foo' => 'bar'];

    /**
     * ORMSessionHandler::__constructor
     *  should
     *    construct new instance
     *  when
     *    valid dependencies are provided
     *
     * @test
     */
    public function beInstanceOfORMSessionHandler()
    {
        $this->assertInstanceOf('Neducatio\ORMSessionHandlerBundle\HttpFoundation\Session\Storage\Handler\ORMSessionHandler', $this->ORMSessionHandler);
    }

    /**
     * ORMSessionHandler::open
     *  should
     *      always return true
     *
     * ORMSessionHandler::close
     *  should
     *      always return true
     *
     * @test
     */
    public function alwaysReturnTrue()
    {
        $this->assertTrue($this->ORMSessionHandler->open(self::SESSION_PATH, self::SESSION_NAME));
        $this->assertTrue($this->ORMSessionHandler->close());
    }

    /**
     * ORMSessionHandler::destroy
     *  should
     *      remove session entity with given session id
     *
     * @test
     */
    public function removeSessionEntityWithGivenSessionId()
    {
        $this->sessionRepositoryExpectsRemoveMethodCall();

        $this->assertTrue($this->ORMSessionHandler->destroy(self::SESSION_ID));
    }

    /**
     * ORMSessionHandler::gc
     *  should
     *      remove outdated sessions
     * @test
     */
    public function removeOutdatedSessions()
    {
        $this->sessionRepositoryExpectsRemoveOutdatedMethodCall();

        $this->assertTrue($this->ORMSessionHandler->gc(self::SESSION_MAXLIFETIME));
    }

    /**
     * ORMSessionHandler::read
     *  should
     *      return session data
     * @test
     */
    public function returnSessionData()
    {
        $this->sessionRepositoryExpectsGetMethodCall($this->getSessionEntity());

        $this->assertEquals(serialize($this->sessionData), $this->ORMSessionHandler->read(self::SESSION_ID));
    }

    /**
     * ORMSessionHandler::write
     *  should
     *      write session data
     *
     * @test
     */
    public function writeSessionData()
    {
        $sessionEntity = $this->getSessionEntity();
        $this->sessionRepositoryExpectsGetMethodCall($sessionEntity);
        $this->sessionRepositoryExpectsSaveMethodCall($sessionEntity);
        $this->sessionWriterExpectsWriteMethodCall($sessionEntity);

        $this->assertTrue($this->ORMSessionHandler->write(self::SESSION_ID, serialize($this->sessionData)));
    }

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->sessionRepository = m::mock('Neducatio\ORMSessionHandlerBundle\Repository\SessionRepository');
        $this->sessionWriter = m::mock('Neducatio\ORMSessionHandlerBundle\HttpFoundation\Session\Storage\Handler\SessionWriterInterface');

        $this->ORMSessionHandler = new ORMSessionHandler($this->sessionRepository, $this->sessionWriter);
    }

    /**
     * {@inheritdoc}
     */
    public function tearDown()
    {
        m::close();
    }

    private function sessionRepositoryExpectsRemoveMethodCall()
    {
        $this->sessionRepository->shouldReceive('remove')->with(['id' => self::SESSION_ID])->once();
    }

    private function sessionRepositoryExpectsRemoveOutdatedMethodCall()
    {
        $this->sessionRepository->shouldReceive('removeOutdated')->with(self::SESSION_MAXLIFETIME)->once();
    }

    private function sessionRepositoryExpectsGetMethodCall($sessionEntity)
    {
        $this->sessionRepository->shouldReceive('get')->with(self::SESSION_ID)->once()->andReturn($sessionEntity);
    }

    private function sessionWriterExpectsWriteMethodCall($sessionEntity)
    {
        $this->sessionWriter->shouldReceive('write')->with(self::SESSION_ID, serialize($this->sessionData), $sessionEntity)->once()->andReturn($sessionEntity);
    }

    private function sessionRepositoryExpectsSaveMethodCall($sessionEntity)
    {
        $this->sessionRepository->shouldReceive('save')->with($sessionEntity)->once();
    }

    private function getSessionEntity()
    {
        $sessionEntity = new Session(self::SESSION_ID);
        $sessionEntity->setData(serialize($this->sessionData));

        return $sessionEntity;
    }
}