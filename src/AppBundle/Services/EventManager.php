<?php
/**
 * Created by PhpStorm.
 * User: ollie
 * Date: 2/1/17
 * Time: 7:22 PM
 */

namespace AppBundle\Services;

use AppBundle\Entity\Club;
use AppBundle\Entity\Event;
use AppBundle\Entity\EventStatus;
use AppBundle\Entity\User;
use AppBundle\Entity\UserVote;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Validator\Constraints\Date;

class EventManager
{
    /**
     * @param User $user
     * @param Event $event
     * @param bool $isVoteYes
     *
     * @return UserVote|mixed|null
     */
    public function vote(User $user, Event $event, $isVoteYes)
    {
        $userVotes = $event->getUserVotes();
        $vote = null;

        if ($userVotes->isEmpty()) {
            $vote = new UserVote();
        } else {
            foreach ($userVotes as $userVote) {
                $vote = $userVote->getUser()->getUsername() == $user->getUsername()
                    ? $userVote
                    : new UserVote()
                ;
            }
        }

        $vote
            ->setUser($user)
            ->setVoteYes($isVoteYes)
        ;
        $event->addUserVote($vote);

        return $vote;
    }

    public function fillEvent(Event $event, Club $club)
    {
        $status = (new EventStatus())
            ->setValue(EventStatus::VOTING_OPEN)
        ;
        $votingEnd = $this->setVotingEndDate($event);

        $event
            ->setVotingStart(new \DateTime('now'))
            ->setClub($club)
            ->setStatus($status)
            ->setVotingEnd($votingEnd)
        ;
    }

    public function setVotingEndDate(Event $event)
    {
        $votingEnd = new \DateTime($event->getDate()->format('Y-m-d'));
        $votingEnd->modify('-1 week');

        return $votingEnd;
    }
}
