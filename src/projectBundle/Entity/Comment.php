<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 23.06.2018
 * Time: 22:11
 */

namespace projectBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class book
 * @package projectBundle\Entity
 * @ORM\Entity
 * @ORM\Table(name="user_comment")
 * @ORM\Entity(repositoryClass="projectBundle\Repository\CommentRepository")
 */
class Comment
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created;


    /**
     *@ORM\Column(type="text")
     */
    private $commentText;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="userComment")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    //много комментов может быть в одиной книге
    /**
     * @ORM\ManyToOne(targetEntity="Book", inversedBy="comment")
     * @ORM\JoinColumn(name="book_id", referencedColumnName="id", onDelete="CASCADE")
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
     * Set created
     *
     * @param \datatime $created
     *
     * @return Comment
     */
    public function setCreated(\datatime $created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \datatime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set comment
     *
     * @param string $comment
     *
     * @return Comment
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->created = new \DateTime();
        $this->book = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add book
     *
     * @param \projectBundle\Entity\Book $book
     *
     * @return Comment
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
     * @return Comment
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
     * @return Comment
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
     * @return Comment
     */
    public function setUser(\projectBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Set commentText
     *
     * @param string $commentText
     *
     * @return Comment
     */
    public function setCommentText($commentText)
    {
        $this->commentText = $commentText;

        return $this;
    }

    /**
     * Get commentText
     *
     * @return string
     */
    public function getCommentText()
    {
        return $this->commentText;
    }
}
