<?php
namespace Neducatio\ORMSessionHandlerBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * SessionRepository
 */
class SessionRepository extends EntityRepository
{
    const ALIAS = 's';

    /**
     * Get session entity
     *
     * @param string $sessionId session id
     *
     * @return \Neducatio\ORMSessionHandler\Model\Session | null
     */
    public function get($sessionId)
    {
        return $this->find($sessionId);
    }

    /**
     * Remove sessions specified by criteria
     *
     * @param array $criteria sessions criteria
     */
    public function remove(array $criteria)
    {
        $queryBuilder = $this->createQueryBuilder(self::ALIAS)->delete();

        foreach ($criteria as $field => $value) {
            $queryBuilder
                ->where(sprintf('%s.%s = :%s', self::ALIAS, $field, $field))
                ->setParameter($field, $value);
        }

        $queryBuilder->getQuery()->execute();
    }

    /**
     *  Remove outdated sessions
     *
     * @param int $maxLifetime max lifetime
     */
    public function removeOutdated($maxLifetime)
    {
        $queryBuilder = $this->createQueryBuilder(self::ALIAS);

        $queryBuilder
            ->delete()
            ->where('s.timestamp < :time')
            ->setParameter('time', time() - $maxLifetime);

        $queryBuilder->getQuery()->execute();
    }
}