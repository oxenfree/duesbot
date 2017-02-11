<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Event
 *
 * @ORM\Table(name="event")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\EventRepository")
 */
class Event
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
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="events")
     */
    private $owner;

    /**
     * @var UserVote[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\UserVote", mappedBy="event", cascade={"remove"})
     */
    private $userVotes;

    /**
     * Date of the event.
     *
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * Time of the event.
     *
     * @var \DateTime
     *
     * @ORM\Column(name="time", type="datetime")
     */
    private $time;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="voting_start", type="datetime")
     */
    private $votingStart;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="voting_end", type="datetime")
     */
    private $votingEnd;

    /**
     * @var Club
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Club", inversedBy="events")
     */
    private $club;

    /**
     * @var int
     *
     * @ORM\Column(name="vote_total", type="integer", nullable=true)
     */
    private $voteTotal;

    /**
     * @var EventStatus
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\EventStatus", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="event_status_id")
     */
    private $status;

    /**
     * Event constructor.
     */
    public function __construct()
    {
        $this->userVotes = new ArrayCollection();
        if (!isset($this->voteTotal))   {
            $this->voteTotal = 1;
        }
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
     * @return Event
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
     * @return Event
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
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Event
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set time
     *
     * @param \DateTime $time
     *
     * @return Event
     */
    public function setTime($time)
    {
        $this->time = $time;

        return $this;
    }

    /**
     * Get time
     *
     * @return \DateTime
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * Set votingStart
     *
     * @param \DateTime $votingStart
     *
     * @return Event
     */
    public function setVotingStart($votingStart)
    {
        $this->votingStart = $votingStart;

        return $this;
    }

    /**
     * Get votingStart
     *
     * @return \DateTime
     */
    public function getVotingStart()
    {
        return $this->votingStart;
    }

    /**
     * Set votingEnd
     *
     * @param \DateTime $votingEnd
     *
     * @return Event
     */
    public function setVotingEnd($votingEnd)
    {
        $this->votingEnd = $votingEnd;

        return $this;
    }

    /**
     * Get votingEnd
     *
     * @return \DateTime
     */
    public function getVotingEnd()
    {
        return $this->votingEnd;
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
     * @return Event
     */
    public function setClub(Club $club)
    {
        $this->club = $club;

        return $this;
    }

    /**
     * @return EventStatus
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param EventStatus $status
     *
     * @return Event
     */
    public function setStatus(EventStatus $status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection|UserVote[]
     */
    public function getUserVotes()
    {
        return $this->userVotes;
    }

    /**
     * @param UserVote $userVote
     *
     * @return Event
     */
    public function addUserVote(UserVote $userVote)
    {
        if (true == $userVote->getVoteYes())    {
            ++$this->voteTotal;
        } else {
            $this->voteTotal--;
        }
        $userVote->setEvent($this);

        $this->userVotes[$userVote->getUser()->getUsername()] = $userVote;

        return $this;

    }

    /**
     * @param UserVote $userVote
     *
     * @return Event
     */
    public function removeUserVote(UserVote $userVote)
    {
        $userVote->setEvent(null);
        $this->userVotes->removeElement($userVote);

        return $this;
    }

    /**
     * @return int
     */
    public function getVoteTotal()
    {
        return $this->voteTotal;
    }

    /**
     * @param int $voteTotal
     *
     * @return Event
     */
    public function setVoteTotal($voteTotal)
    {
        $this->voteTotal = $voteTotal;

        return $this;
    }

    /**
     * @return User
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * @param User $owner
     *
     * @return Event
     */
    public function setOwner($owner)
    {
        $this->owner = $owner;

        return $this;
    }
}
