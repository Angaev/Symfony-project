<?php

namespace projectBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class book
 * @package projectBundle\Entity
 * @ORM\Entity
 * @ORM\Table(name="publishing_house")
 */
class publishing_house
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
     * @ORM\OneToMany(targetEntity="Book", mappedBy="publishing_house")
     */
    private $house;

    

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
//     * Constructor
//     */
//    public function __construct()
//    {
//        $this->house = new ArrayCollection();
//    }

    /**
     * Add house
     *
     * @param \projectBundle\Entity\Book $house
     *
     * @return publishing_house
     */
    public function addHouse(\projectBundle\Entity\Book $house)
    {
        $this->house[] = $house;

        return $this;
    }

    /**
     * Remove house
     *
     * @param \projectBundle\Entity\Book $house
     */
    public function removeHouse(\projectBundle\Entity\Book $house)
    {
        $this->house->removeElement($house);
    }

    /**
     * Get house
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getHouse()
    {
        return $this->house;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return publishing_house
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

    public function __toString()
    {
        return $this->name;
    }
}
