<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 21.07.2018
 * Time: 21:02
 */

namespace projectBundle\Repository;

use Doctrine\ORM\EntityRepository;

class CommentRepository extends EntityRepository
{
    public function getLastUserComment($user)
    {
        $em = $this->getEntityManager();
        $qry = $em->createQuery('
            SELECT c FROM projectBundle:comment c 
            WHERE c.user = :user 
            ORDER BY c.id DESC 
        ');
        $qry->setParameter('user', $user);
        $qry->setMaxResults(1);

        return $qry->getResult();
    }
}