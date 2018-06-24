<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 24.06.2018
 * Time: 22:01
 */

namespace projectBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class book
 * @package projectBundle\Entity
 * @ORM\Entity
 * @ORM\Table(name="user")
 */
class user
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @ORM\Column(type="string")
     */
    private $email;

    /**
     * @ORM\Column(type="string")
     */
    private $avatar;

    /**
     * @ORM\Column(type="string")
     */
    private $passHash;


    /**
     * @ORM\Column(type="boolean")
     */
    private $isAdmin;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isBan;

    /**
     * @ORM\ManyToMany(targetEntity="projectBundle\Entity\like", mappedBy="user")
     * @ORM\JoinTable(name="user_likes_matching")
     */
    private $like;

    /**
     * @ORM\ManyToMany(targetEntity="projectBundle\Entity\comment", mappedBy="user")
     * @ORM\JoinTable(name="user_comment_matching")
     */
    private $comment;


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
     * Set name
     *
     * @param string $name
     *
     * @return user
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return user
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set avatar
     *
     * @param string $avatar
     *
     * @return user
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * Get avatar
     *
     * @return string
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * Set passHash
     *
     * @param string $passHash
     *
     * @return user
     */
    public function setPassHash($passHash)
    {
        $this->passHash = $passHash;

        return $this;
    }

    /**
     * Get passHash
     *
     * @return string
     */
    public function getPassHash()
    {
        return $this->passHash;
    }

    /**
     * Set isAdmin
     *
     * @param boolean $isAdmin
     *
     * @return user
     */
    public function setIsAdmin($isAdmin)
    {
        $this->isAdmin = $isAdmin;

        return $this;
    }

    /**
     * Get isAdmin
     *
     * @return boolean
     */
    public function getIsAdmin()
    {
        return $this->isAdmin;
    }

    /**
     * Set isBan
     *
     * @param boolean $isBan
     *
     * @return user
     */
    public function setIsBan($isBan)
    {
        $this->isBan = $isBan;

        return $this;
    }

    /**
     * Get isBan
     *
     * @return boolean
     */
    public function getIsBan()
    {
        return $this->isBan;
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
     * @param \projectBundle\Entity\like $book
     *
     * @return user
     */
    public function addBook(\projectBundle\Entity\like $book)
    {
        $this->book[] = $book;

        return $this;
    }

    /**
     * Remove book
     *
     * @param \projectBundle\Entity\like $book
     */
    public function removeBook(\projectBundle\Entity\like $book)
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
     * Add like
     *
     * @param \projectBundle\Entity\like $like
     *
     * @return user
     */
    public function addLike(\projectBundle\Entity\like $like)
    {
        $this->like[] = $like;

        return $this;
    }

    /**
     * Remove like
     *
     * @param \projectBundle\Entity\like $like
     */
    public function removeLike(\projectBundle\Entity\like $like)
    {
        $this->like->removeElement($like);
    }

    /**
     * Get like
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLike()
    {
        return $this->like;
    }

    /**
     * Add comment
     *
     * @param \projectBundle\Entity\comment $comment
     *
     * @return user
     */
    public function addComment(\projectBundle\Entity\comment $comment)
    {
        $this->comment[] = $comment;

        return $this;
    }

    /**
     * Remove comment
     *
     * @param \projectBundle\Entity\comment $comment
     */
    public function removeComment(\projectBundle\Entity\comment $comment)
    {
        $this->comment->removeElement($comment);
    }

    /**
     * Get comment
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getComment()
    {
        return $this->comment;
    }
}
