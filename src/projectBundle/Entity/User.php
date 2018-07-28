<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 24.06.2018
 * Time: 22:01
 */

namespace projectBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * Class book
 * @package projectBundle\Entity
 * @ORM\Entity
 * @ORM\Table(name="user")
 * @UniqueEntity(fields="email", message="This email address is already in use")
 */
class User implements UserInterface
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="user")
     */
    private $userComment;

    /**
     * @ORM\OneToMany(targetEntity="Like", mappedBy="user")
     */
    private $userLike;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     *
     */
    protected $email;

    /**
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * @ORM\Column(type="string", length=50)
     */
    protected $role;


    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $avatar;

    /**
     * @Assert\Length(max=4096)
     */
    protected $plainPassword;

    /**
     * @ORM\Column(type="string", length=64)
     */
    protected $password;


    public function eraseCredentials()
    {
        return null;
    }

    public function getRole()
    {
        return $this->role;
    }

    public function setRole($role = null)
    {
        $this->role = $role;
    }

    public function getRoles()
    {
        return [$this->getRole()];
    }

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
     * @return User
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
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    public function getEmail()
    {
        return $this->email;
    }


    /**
     * Get email
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->email;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    public function setPlainPassword($plainPassword)
    {
        $this->plainPassword = $plainPassword;
    }

    public function getSalt()
    {
        return null;
    }

    /**
     * Set avatar
     *
     * @param string $avatar
     *
     * @return User
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
     * Constructor
     */
    public function __construct()
    {
        $this->book = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add book
     *
     * @param \projectBundle\Entity\Like $book
     *
     * @return User
     */
    public function addBook(\projectBundle\Entity\Like $book)
    {
        $this->book[] = $book;

        return $this;
    }

    /**
     * Remove book
     *
     * @param \projectBundle\Entity\Like $book
     */
    public function removeBook(\projectBundle\Entity\Like $book)
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
     * @param \projectBundle\Entity\Like $like
     *
     * @return User
     */
    public function addLike(\projectBundle\Entity\Like $like)
    {
        $this->like[] = $like;

        return $this;
    }

    /**
     * Remove like
     *
     * @param \projectBundle\Entity\Like $like
     */
    public function removeLike(\projectBundle\Entity\Like $like)
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
     * @param \projectBundle\Entity\Comment $comment
     *
     * @return User
     */
    public function addComment(\projectBundle\Entity\Comment $comment)
    {
        $this->comment[] = $comment;

        return $this;
    }

    /**
     * Remove comment
     *
     * @param \projectBundle\Entity\Comment $comment
     */
    public function removeComment(\projectBundle\Entity\Comment $comment)
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

    /**
     * Add user
     *
     * @param \projectBundle\Entity\Comment $user
     *
     * @return User
     */
    public function addUser(\projectBundle\Entity\Comment $user)
    {
        $this->user[] = $user;

        return $this;
    }

    /**
     * Remove user
     *
     * @param \projectBundle\Entity\Comment $user
     */
    public function removeUser(\projectBundle\Entity\Comment $user)
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
     * Add userComment
     *
     * @param \projectBundle\Entity\Comment $userComment
     *
     * @return User
     */
    public function addUserComment(\projectBundle\Entity\Comment $userComment)
    {
        $this->userComment[] = $userComment;

        return $this;
    }

    /**
     * Remove userComment
     *
     * @param \projectBundle\Entity\Comment $userComment
     */
    public function removeUserComment(\projectBundle\Entity\Comment $userComment)
    {
        $this->userComment->removeElement($userComment);
    }

    /**
     * Get userComment
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUserComment()
    {
        return $this->userComment;
    }

    /**
     * Add userLike
     *
     * @param \projectBundle\Entity\Like $userLike
     *
     * @return User
     */
    public function addUserLike(\projectBundle\Entity\Like $userLike)
    {
        $this->userLike[] = $userLike;

        return $this;
    }

    /**
     * Remove userLike
     *
     * @param \projectBundle\Entity\Like $userLike
     */
    public function removeUserLike(\projectBundle\Entity\Like $userLike)
    {
        $this->userLike->removeElement($userLike);
    }

    /**
     * Get userLike
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUserLike()
    {
        return $this->userLike;
    }
}
