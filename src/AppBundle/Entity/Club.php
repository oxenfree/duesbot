<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Club
 *
 * @ORM\Table(name="club")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ClubRepository")
 */
class Club
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var float
     *
     * @ORM\Column(name="treasury", type="float")
     */
    private $treasury;

    /**
     * @var Member[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Member", mappedBy="club", cascade={"persist"})
     */
    private $members;

    /**
     * @var Event[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Event", mappedBy="club", cascade={"persist"})
     */
    private $events;

    /**
     * Club constructor.
     */
    public function __construct()
    {
        $this->members = new ArrayCollection();
        $this->events = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return int
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
     * @return Club
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
     * Set description
     *
     * @param string $description
     *
     * @return Club
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
     * Set treasury
     *
     * @param float $treasury
     *
     * @return Club
     */
    public function setTreasury($treasury)
    {
        $this->treasury = $treasury;

        return $this;
    }

    /**
     * Get treasury
     *
     * @return float
     */
    public function getTreasury()
    {
        return $this->treasury;
    }

    /**
     * Get members
     *
     * @return Collection|Member[]
     */
    public function getMembers()
    {
        return $this->members;
    }

    /**
     * Add member
     *
     * @param Member $member
     *
     * @return Club
     */
    public function addMember(Member $member)
    {
        $member->setClub($this);
        $this->members[] = $member;

        return $this;
    }

    /**
     * Remove member
     *
     * @param Member $member
     *
     * @return Club
     */
    public function removeMember(Member $member)
    {
        $this->members->removeElement($member);

        return $this;
    }

    /**
     * Get events
     *
     * @return Collection|Event[]
     */
    public function getEvents()
    {
        return $this->events;
    }

    /**
     * Add event
     *
     * @param Event $event
     *
     * @return Club
     */
    public function addEvent(Event $event)
    {
        $event->setClub($this);
        $this->events[] = $event;

        return $this;
    }

    /**
     * Remove event
     *
     * @param Event $event
     *
     * @return Club
     */
    public function removeEvent(Event $event)
    {
        $this->events->removeElement($event);

        return $this;
    }
}
