<?php
namespace Neducatio\NOneBundle\Tests\Repository;

use Neducatio\ORMSessionHandlerBundle\Repository\SessionRepository;
use Mockery as m;

/**
 * Unit tests for Neducatio\ORMSessionHandlerBundle\Repository\SessionRepository
 *
 * @covers Neducatio\ORMSessionHandlerBundle\Repository\SessionRepository
 */
class SessionRepositoryShould extends \PHPUnit_Framework_TestCase
{
    const MAX_LIFETIME = 5;
    const N_ONE_ID = 10;
    const SESSION_ID = '212das123we';

    private $sessionRepository;
    private $entityManager;
    private $queryBuilder;

    /**
     * SessionRepository::__constructor
     *  should
     *    construct new instance
     *  when
     *    valid dependencies are provided
     *
     * @test
     */
    public function beInstanceOfSessionRepository()
    {
        $this->assertInstanceOf('Neducatio\ORMSessionHandlerBundle\Repository\SessionRepository', $this->sessionRepository);
    }

    /**
     * SessionRepository::get
     *  should
     *      get session with given id
     *
     * @test
     */
    public function getSessionWithGivenId()
    {
        $this->sessionShouldBeRetrieved();

        $this->sessionRepository->get(self::SESSION_ID);
    }

    /**
     * SessionRepository::removeOutdated
     *  should
     *    remove outdated sessions
     *
     * @test
     */
    public function removeOutdatedSessions()
    {
        $this->outdatedSessionsShouldBeRemoved();

        $this->sessionRepository->removeOutdated(self::MAX_LIFETIME);
    }

    /**
     * SessionRepository::remove
     *  should
     *    destroy specifed sessions
     *
     * @test
     */
    public function removeSpecifiedSessions()
    {
        $criteria = ['nOneId' => self::N_ONE_ID];
        $this->specifiedSessionsShouldBeRemoved($criteria);

        $this->sessionRepository->remove($criteria);
    }

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->prepareEntityManager();

        $this->sessionRepository = new SessionRepository($this->entityManager, m::mock('Doctrine\ORM\Mapping\ClassMetadata'));
    }

    /**
     * {@inheritdoc}
     */
    public function tearDown()
    {
      parent::tearDown();
      m::close();
    }

    private function prepareEntityManager()
    {
        $this->queryBuilder = m::mock('Doctrine\ORM\QueryBuilder');
        $this->queryBuilder->shouldReceive('select->from')->andReturn($this->queryBuilder);

        $this->entityManager = m::mock('Doctrine\ORM\EntityManager')
            ->shouldReceive('createQueryBuilder')->andReturn($this->queryBuilder)
            ->getMock();
    }

    private function outdatedSessionsShouldBeRemoved()
    {
        $this->queryBuilder->shouldReceive('delete')->andReturn($this->queryBuilder)->once();
        $this->queryBuilder->shouldReceive('where')->with('s.timestamp < :time')->andReturn($this->queryBuilder)->once();
        $this->queryBuilder->shouldReceive('getQuery->execute')->once();
        $this->queryBuilder->shouldReceive('setParameter')->with('time', m::type('int'))->andReturn($this->queryBuilder)->once();
    }

    private function specifiedSessionsShouldBeRemoved(array $criteria)
    {
        $this->queryBuilder->shouldReceive('delete')->andReturn($this->queryBuilder)->once();

        foreach($criteria as $field => $value) {
          $this->queryBuilder->shouldReceive('where')->with(sprintf('%s.%s = :%s', SessionRepository::ALIAS, $field, $field))->andReturn($this->queryBuilder)->once();
          $this->queryBuilder->shouldReceive('setParameter')->with($field, $value)->andReturn($this->queryBuilder)->once();
        }

        $this->queryBuilder->shouldReceive('getQuery->execute')->once();
    }

    private function sessionShouldBeRetrieved()
    {
        $this->entityManager->shouldReceive('find')->once();
    }

}
