<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * TaskRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TaskRepository extends EntityRepository
{
    public function getAll()
    {
        $query = $this->createQueryBuilder('task')
            ->orderBy('task.createdAt', 'DESC')
            ->getQuery()
        ;

        return $query->getResult();
    }
}