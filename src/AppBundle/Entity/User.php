<?php
// src/AppBundle/Entity/User.php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
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
     * @var Club
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Club", inversedBy="users")
     */
    private $club;

    /**
     * @var Event[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Event", mappedBy="owner", cascade={"persist", "remove"})
     */
    private $events;

    /**
     * @var string
     *
     * @ORM\Column(name="stripe_customer_id", type="string", unique=true, nullable=true)
     */
    private $stripeCustomerId;

    /**
     * User constructor.
     */
    public function __construct()
    {
        parent::__construct();
        if (empty($this->roles))    {
            $this->addRole('ROLE_USER');
        }

        $this->events = new ArrayCollection();
    }

    /**
     * @return Club
     */
    public function getClub()
    {
        return $this->club;
    }

    /**
     * @param Club $club
     *
     * @return User
     */
    public function setClub($club)
    {
        $this->club = $club;

        return $this;
    }

    /**
     * @return ArrayCollection|Event[]
     */
    public function getEvents()
    {
        return $this->events;
    }

    /**
     * @param Event $event
     *
     * @return User
     */
    public function addEvent(Event $event)
    {
        $this->events[] = $event;
        $event->setOwner($this);

        return $this;
    }

    /**
     * @param Event $event
     *
     * @return User
     */
    public function removeEvent(Event $event)
    {
        $this->events->removeElement($event);
        $event->setOwner(null);

        return $this;
    }

    /**
     * @param mixed $events
     * @return User
     */
    public function setEvents($events)
    {
        $this->events = $events;
        return $this;
    }

    /**
     * @return string
     */
    public function getStripeCustomerId()
    {
        return $this->stripeCustomerId;
    }

    /**
     * @param string $stripeCustomerId
     *
     * @return User
     */
    public function setStripeCustomerId($stripeCustomerId)
    {
        $this->stripeCustomerId = $stripeCustomerId;

        return $this;
    }
}
