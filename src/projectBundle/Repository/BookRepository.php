<?php

namespace projectBundle\Repository;


use Doctrine\ORM\EntityRepository;

class BookRepository extends EntityRepository
{
    public function searchByWord($word)
    {
        $qry = $this->createQueryBuilder('b')->where('b.name LIKE :word');
        $qry->setParameter('word', '%' . $word . '%');
        return $qry->getQuery()->getResult();
    }

    public function getTop50()
    {
        $qry = $this->createQueryBuilder('b')

                ->setMaxResults(50);
        return $qry->getQuery()->getResult();
    }
}