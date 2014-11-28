<?php
namespace Neducatio\ORMSessionHandlerBundle\Tests\Model;

use Mockery as m;

/**
 * SessionShould
 *
 * @covers Neducatio\ORMSessionHandlerBundle\Model\Session
 */
class SessionShould extends \PHPUnit_Framework_TestCase
{
    const ID = 'k1u2r3e5w6k7a';

    /**
     * @var SessionShould
     */
    private $sessionEntity;

    /**
     * Session::__constructor
     *  should
     *    construct new instance
     *  when
     *    valid dependencies are provided
     *
     * @test
     */
    public function beInstanceOfSession()
    {
        $this->assertInstanceOf('Neducatio\ORMSessionHandlerBundle\Model\Session', $this->sessionEntity);
    }

    /**
     * Session::getId
     *  should
     *      get id passed in constructor
     *
     * @test
     */
    public function getIdPassedInConstructor()
    {
        $this->assertEquals(self::ID, $this->sessionEntity->getId());
    }

    /**
     * Session::getData
     *  should
     *      return previously set data (via Session::setData)
     *
     * @test
     */
    public function returnPreviouslySetData()
    {
        $_SESSION = ['foo' => 'bar', 'object' => new \stdClass()];

        $serializedSession = serialize($_SESSION);

        $this->sessionEntity->setData($serializedSession);

        $this->assertEquals($serializedSession, $this->sessionEntity->getData());
    }

    /**
     * Session::getTimestamp
     *  should
     *      get provioulsy updated timestamp
     *
     * @test
     */
    public function getProvioulsyUpdatedTimestamp()
    {
        $currentTimestamp = time();
        $this->sessionEntity->updateTimestamp();
        $this->assertTrue(is_int($this->sessionEntity->getTimestamp()));
        $this->assertTrue($currentTimestamp <= $this->sessionEntity->getTimestamp());
    }

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->sessionEntity = new Session(self::ID);
    }

    /**
     * {@inheritdoc}
     */
    public function tearDown()
    {
        m::close();
    }
}