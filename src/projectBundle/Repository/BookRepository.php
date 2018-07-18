<?php

namespace projectBundle\Repository;


use Doctrine\ORM\EntityRepository;
use projectBundle\projectBundle;

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
        $em = $this->getEntityManager();
        $likeSort = $em->createQuery('
            SELECT COUNT(l.id) FROM projectBundle:like l
            ORDER BY (l.id)
            GROUP BY l.book 
        ');

        return $likeSort->getResult();


//        $qry = $this->createQueryBuilder('b')
//                ->setMaxResults(50);


        $query = $em->createQuery('
            SELECT b FROM projectBundle:book b
            LEFT JOIN b.like l 
            
        ');

        return $query->getResult();
//        return $qry->getQuery()->getResult();
    }
}