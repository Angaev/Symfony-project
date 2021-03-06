<?php

namespace projectBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Class book
 * @package projectBundle\Entity
 * @ORM\Entity(repositoryClass="projectBundle\Repository\BookRepository")
 * @ORM\Table(name="book")
 */
class Book
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
     * @ORM\Column(type="integer")
     */
    private $year;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Assert\NotBlank()
     * @Assert\Image()
     */
    private $image;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="text")
     */
    private $link;

    /**
     * @ORM\ManyToOne(targetEntity="PublishingHouse", inversedBy="house")
     * @ORM\JoinColumn(name="publishing_house_id", referencedColumnName="id")
     */
    private $publishing_house;

    //в одной книге много лайков (One-to-many)
    /**
     * @ORM\OneToMany(targetEntity="Like", mappedBy="book")
     */
    private $like;

    //в одной книге много комментов (One-to-many)
    /**
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="book")
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
     * @return Book
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
     * Set year
     *
     * @param integer $year
     *
     * @return Book
     */
    public function setYear($year)
    {
        $this->year = $year;

        return $this;
    }

    /**
     * Get year
     *
     * @return integer
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * Set image
     *
     * @param string $image
     *
     * @return Book
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Book
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set link
     *
     * @param string $link
     *
     * @return Book
     */
    public function setLink($link)
    {
        $this->link = $link;

        return $this;
    }

    /**
     * Get link
     *
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * Set publishingHouse
     *
     * @param \projectBundle\Entity\PublishingHouse $publishingHouse
     *
     * @return Book
     */
    public function setPublishingHouse(\projectBundle\Entity\PublishingHouse $publishingHouse = null)
    {
        $this->publishing_house = $publishingHouse;

        return $this;
    }

    /**
     * Get publishingHouse
     *
     * @return \projectBundle\Entity\PublishingHouse
     */
    public function getPublishingHouse()
    {
        return $this->publishing_house;
    }

    /**
     * Set comment
     *
     * @param \projectBundle\Entity\Comment $comment
     *
     * @return Book
     */
    public function setComment(\projectBundle\Entity\Comment $comment = null)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return \projectBundle\Entity\Comment
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
        $this->comment = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add comment
     *
     * @param \projectBundle\Entity\Comment $comment
     *
     * @return Book
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
     * Add like
     *
     * @param \projectBundle\Entity\Like $like
     *
     * @return Book
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
}
