<?php

namespace projectBundle\Repository;


use Doctrine\ORM\EntityRepository;
use projectBundle\projectBundle;

class BookRepository extends EntityRepository
{
    public function searchByWord($word)
    {
        $em = $this->getEntityManager();
        $rawSql = '
            SELECT book.id, book.name, book.year, book.image, book.description, 
            (SELECT COUNT(DISTINCT id) FROM user_like WHERE user_like.book_id = book.id) as likeCount,
            (SELECT COUNT(DISTINCT id) FROM user_comment WHERE user_comment.book_id = book.id) as commentCount 
            FROM Book
            WHERE
            book.name like '%' :searchWord '%'
        ';
        $statement = $em->getConnection()->prepare($rawSql);
        $statement->bindValue('searchWord', $word);
        ($statement->execute());


        return $result = $statement->fetchAll();
    }

    public function getLastUserBook($userId, $quantity)
    {
        $em = $this->getEntityManager();
        $qry = $em->createQuery('
            SELECT l FROM projectBundle:like l
            WHERE l.user = :user
            ORDER BY l.id DESC 
        ');
        $qry->setParameter('user', $userId);

        $qry->setMaxResults($quantity);
        return $qry->getResult();
    }

    public function getLikedBooks($userId)
    {
        $em = $this->getEntityManager();

        $rawSql = "
            SELECT book.id, book.name, book.year, book.image, book.description, 
            (SELECT COUNT(DISTINCT id) FROM user_like WHERE user_like.book_id = book.id) as likeCount,
            (SELECT COUNT(DISTINCT id) FROM user_comment WHERE user_comment.book_id = book.id) as commentCount 
            FROM Book
            WHERE book.id IN
            (SELECT book_id FROM user_like WHERE user_id = :usr)
        ";

        $statement = $em->getConnection()->prepare($rawSql);
        $statement->bindValue('usr', $userId);
        $statement->execute();

        return $result = $statement->fetchAll();

    }

    public function getTop50()
    {
        $em = $this->getEntityManager();
        $rawSql = "
            SELECT book.id, book.name, book.year, book.image,
            (SELECT COUNT(DISTINCT id) FROM user_like WHERE user_like.book_id = book.id) as likeCount,
            (SELECT COUNT(DISTINCT id) FROM user_comment WHERE user_comment.book_id = book.id) as commentCount
            FROM book
            ORDER BY likeCount DESC
            LIMIT 50
        ";


        $statement = $em->getConnection()->prepare($rawSql);
        $statement->execute();

        return $result = $statement->fetchAll();
    }

    public function getAllBooks()
    {
        $em = $this->getEntityManager();
        $rawSql = "
            SELECT book.id, book.name, book.year, book.image, book.description, 
            (SELECT COUNT(DISTINCT id) FROM user_like WHERE user_like.book_id = book.id) as likeCount,
            (SELECT COUNT(DISTINCT id) FROM user_comment WHERE user_comment.book_id = book.id) as commentCount 
            FROM Book
            ORDER BY book.id ASC 
        ";
        $statement = $em->getConnection()->prepare($rawSql);
        $statement->execute();

        return $statement->fetchAll();
    }
}