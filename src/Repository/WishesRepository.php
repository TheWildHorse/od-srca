<?php

namespace App\Repository;

use App\Entity\Wishes;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;


class WishesRepository extends EntityRepository
{
    /**
     * @param Wishes $entry
     * @return void
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function createWish(Wishes $entry)
    {
        $this->getEntityManager()->persist($entry);
        $this->getEntityManager()->flush();
    }

    /**
     * @param $ids
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getByIds($ids)
    {
        $dql = $this->createQueryBuilder("w")
            ->andWhere("w.id in (:ids)")
            ->setParameter('ids', $ids);
        return $dql;
    }

    /**
     * @return \Doctrine\ORM\Query
     */
    public function orderByCity()
    {
        $db = $this->createQueryBuilder("w")
            ->orderBy('w.location')
            ->getQuery()
            ->getResult();

        return $db;
    }
}
