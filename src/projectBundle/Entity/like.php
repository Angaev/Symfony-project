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

    /**
     * @ORM\Column(type="datetime")
     */
    private $created;



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
     * @param \DateTime $created
     *
     * @return like
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }
}
