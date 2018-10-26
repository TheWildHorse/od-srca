<?php

namespace App\Repository;

use App\Entity\Wishes;
use Doctrine\ORM\EntityRepository;

class WishesRepository extends EntityRepository
{
    /**
     * @param Wishes $entry
     * @return void
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function createWish(Wishes $entry){
        $this->getEntityManager()->persist($entry);
        $this->getEntityManager()->flush();
    }
}
