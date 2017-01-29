<?php
/**
 * Created by PhpStorm.
 * User: ollie
 * Date: 1/28/17
 * Time: 5:48 PM
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class UserVote
 * @package AppBundle\Entity
 * @ORM\Table(name="user_vote")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserVoteRepository")
 */
class UserVote
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
     * @var Event
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Event", inversedBy="userVotes")
     */
    private $event;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     */
    private $user;

    /**
     * @var bool
     *
     * @ORM\Column(name="vote_yes", type="boolean", nullable=true)
     */
    private $voteYes;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Event
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * @param Event $event
     * @return UserVote
     */
    public function setEvent($event)
    {
        $this->event = $event;
        return $this;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return UserVote
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getVoteYes()
    {
        return $this->voteYes;
    }

    /**
     * @param mixed $voteYes
     * @return UserVote
     */
    public function setVoteYes($voteYes)
    {
        $this->voteYes = $voteYes;
        return $this;
    }
}
