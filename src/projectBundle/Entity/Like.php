<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 24.06.2018
 * Time: 18:56
 */

namespace projectBundle\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * Class book
 * @package projectBundle\Entity
 * @ORM\Entity
 * @ORM\Table(name="user_like")
 */
class Like
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

//    /**
//     * @ORM\Column(type="datetime")
//     */
//    private $created;

    //много лайков у одного пользователя
    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="userLike")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    //много лайков может быть в одиной книге
    /**
     * @ORM\ManyToOne(targetEntity="Book", inversedBy="like")
     * @ORM\JoinColumn(name="book_id", referencedColumnName="id")
     */
    private $book;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->book = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add book
     *
     * @param \projectBundle\Entity\Book $book
     *
     * @return Like
     */
    public function addBook(\projectBundle\Entity\Book $book)
    {
        $this->book[] = $book;

        return $this;
    }

    /**
     * Remove book
     *
     * @param \projectBundle\Entity\Book $book
     */
    public function removeBook(\projectBundle\Entity\Book $book)
    {
        $this->book->removeElement($book);
    }

    /**
     * Get book
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBook()
    {
        return $this->book;
    }

    /**
     * Add user
     *
     * @param \projectBundle\Entity\User $user
     *
     * @return Like
     */
    public function addUser(\projectBundle\Entity\User $user)
    {
        $this->user[] = $user;

        return $this;
    }

    /**
     * Remove user
     *
     * @param \projectBundle\Entity\User $user
     */
    public function removeUser(\projectBundle\Entity\User $user)
    {
        $this->user->removeElement($user);
    }

    /**
     * Get user
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set book
     *
     * @param \projectBundle\Entity\Book $book
     *
     * @return Like
     */
    public function setBook(\projectBundle\Entity\Book $book = null)
    {
        $this->book = $book;

        return $this;
    }

    /**
     * Set user
     *
     * @param \projectBundle\Entity\User $user
     *
     * @return Like
     */
    public function setUser(\projectBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }
}
