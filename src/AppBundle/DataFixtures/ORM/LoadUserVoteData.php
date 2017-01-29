<?php
/**
 * Created by PhpStorm.
 * User: ollie
 * Date: 1/28/17
 * Time: 8:34 PM
 */

namespace AppBundle\DataFixtures\ORM;


use AppBundle\Entity\UserVote;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadUserVoteData extends AbstractFixture implements OrderedFixtureInterface
{

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $halloween = $this->getReference('Haloween 2017');
        $newYear = $this->getReference('New Years Eve 2017');
        $flyFriend = $this->getReference('Fly in a friend');

        $ollie = $this->getReference('member_Ollie');
        $kip = $this->getReference('member_Kip');
        $brick = $this->getReference('member_Brick');

        $userVoteTraits = [
            [
                'voteYes' => true,
                'event' => $halloween,
                'user' => $ollie
            ],
            [
                'voteYes' => true,
                'event' => $newYear,
                'user' => $ollie,
            ],
            [
                'voteYes' => false,
                'event' => $flyFriend,
                'user' => $ollie,
            ],
            [
                'voteYes' => true,
                'event' => $halloween,
                'user' => $kip
            ],
            [
                'voteYes' => true,
                'event' => $newYear,
                'user' => $kip,
            ],
            [
                'voteYes' => false,
                'event' => $flyFriend,
                'user' => $kip,
            ],
            [
                'voteYes' => true,
                'event' => $halloween,
                'user' => $brick
            ],
            [
                'voteYes' => true,
                'event' => $newYear,
                'user' => $brick,
            ],
            [
                'voteYes' => false,
                'event' => $flyFriend,
                'user' => $brick,
            ]
        ];

        foreach ($userVoteTraits as $userVoteTrait) {
            $userVote = new UserVote();
            $userVote
                ->setVoteYes($userVoteTrait['voteYes'])
                ->setUser($userVoteTrait['user'])
            ;
            if ($userVoteTrait['event'] == $halloween)  {
                $halloween->addUserVote($userVote);
            }
            if ($userVoteTrait['event'] == $flyFriend)  {
                $flyFriend->addUserVote($userVote);
            }
            if ($userVoteTrait['event'] == $newYear)    {
                $newYear->addUserVote($userVote);
            }
            $manager->persist($userVote);
        }
        $manager->flush();

    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 4;
    }
}
