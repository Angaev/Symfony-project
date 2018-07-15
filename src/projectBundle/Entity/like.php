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
class like
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
     * @ORM\ManyToOne(targetEntity="projectBundle\Entity\user", inversedBy="userLike")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    //много лайков может быть в одиной книге
    /**
     * @ORM\ManyToOne(targetEntity="projectBundle\Entity\book", inversedBy="like")
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

//    /**
//     * Set created
//     *
//     * @param \DateTime $created
//     *
//     * @return like
//     */
//    public function setCreated($created)
//    {
//        $this->created = $created;
//
//        return $this;
//    }
//
//    /**
//     * Get created
//     *
//     * @return \DateTime
//     */
//    public function getCreated()
//    {
//        return $this->created;
//    }
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
     * @param \projectBundle\Entity\book $book
     *
     * @return like
     */
    public function addBook(\projectBundle\Entity\book $book)
    {
        $this->book[] = $book;

        return $this;
    }

    /**
     * Remove book
     *
     * @param \projectBundle\Entity\book $book
     */
    public function removeBook(\projectBundle\Entity\book $book)
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
     * @param \projectBundle\Entity\user $user
     *
     * @return like
     */
    public function addUser(\projectBundle\Entity\user $user)
    {
        $this->user[] = $user;

        return $this;
    }

    /**
     * Remove user
     *
     * @param \projectBundle\Entity\user $user
     */
    public function removeUser(\projectBundle\Entity\user $user)
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
     * @param \projectBundle\Entity\book $book
     *
     * @return like
     */
    public function setBook(\projectBundle\Entity\book $book = null)
    {
        $this->book = $book;

        return $this;
    }

    /**
     * Set user
     *
     * @param \projectBundle\Entity\user $user
     *
     * @return like
     */
    public function setUser(\projectBundle\Entity\user $user = null)
    {
        $this->user = $user;

        return $this;
    }
}
