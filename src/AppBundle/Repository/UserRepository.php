<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * UserRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserRepository extends EntityRepository
{
    public function getAll()
    {
        $query = $this->createQueryBuilder('user')
            ->getQuery()
        ;

        $query->useQueryCache(true);
        $query->useResultCache(true);
        $query->setResultCacheLifetime(3600);

        return $query->getResult();
    }
}
