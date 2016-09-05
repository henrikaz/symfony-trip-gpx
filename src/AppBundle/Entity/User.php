<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="Trip", mappedBy="user")
     */
    protected $trips;

    public function __construct() {
        parent::__construct();

        $this->trips = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getTrips()
    {
        return $this->trips;
    }

    /**
     * @param mixed $trips
     */
    public function setTrips($trips)
    {
        $this->trips = $trips;
    }


    /**
     * Add trips
     *
     * @param \AppBundle\Entity\Trip $trips
     * @return User
     */
    public function addTrip(\AppBundle\Entity\Trip $trips)
    {
        $this->trips[] = $trips;

        return $this;
    }

    /**
     * Remove trips
     *
     * @param \AppBundle\Entity\Trip $trips
     */
    public function removeTrip(\AppBundle\Entity\Trip $trips)
    {
        $this->trips->removeElement($trips);
    }
}
