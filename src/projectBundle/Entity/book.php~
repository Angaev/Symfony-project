<?php

namespace projectBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class book
 * @package projectBundle\Entity
 * @ORM\Entity
 * @ORM\Table(name="book")
 */
class book
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * 
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
     * @ORM\Column(type="string")
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
     * @ORM\ManyToOne(targetEntity="projectBundle\Entity\publishing_house", inversedBy="house")
     * @ORM\JoinColumn(name="publishing_house_id", referencedColumnName="id")
     */
    private $publishing_house;


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
     * @return book
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
     * @return book
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
     * @return book
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
     * @return book
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
     * @return book
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
     * @param \projectBundle\Entity\publishing_house $publishingHouse
     *
     * @return book
     */
    public function setPublishingHouse(\projectBundle\Entity\publishing_house $publishingHouse = null)
    {
        $this->publishing_house = $publishingHouse;

        return $this;
    }

    /**
     * Get publishingHouse
     *
     * @return \projectBundle\Entity\publishing_house
     */
    public function getPublishingHouse()
    {
        return $this->publishing_house;
    }

    /**
     * Set comment
     *
     * @param \projectBundle\Entity\comment $comment
     *
     * @return book
     */
    public function setComment(\projectBundle\Entity\comment $comment = null)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return \projectBundle\Entity\comment
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
     * @param \projectBundle\Entity\comment $comment
     *
     * @return book
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
     * Add like
     *
     * @param \projectBundle\Entity\like $like
     *
     * @return book
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
}
