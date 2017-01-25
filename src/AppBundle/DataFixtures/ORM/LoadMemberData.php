<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Member;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadMemberData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $memberTraits = [
            [
                'firstName' => 'Fritz',
                'lastName' => 'Duebat',
                'nickName' => 'Brick',
                'phone' => 66,
                'email' => 'asolivieri@gmail.com',
                'plainPassword' => 'password',
            ],
            [
                'firstName' => 'Frantz',
                'lastName' => 'Twobat',
                'nickName' => 'Kip',
                'phone' => 66,
                'email' => 'member@duebat.com',
                'plainPassword' => 'password',
            ],
            [
                'firstName' => 'Frank',
                'lastName' => 'Duerat',
                'nickName' => 'Kerry',
                'phone' => 66,
                'email' => 'member2@duebat.com',
                'plainPassword' => 'password',
            ],

        ];

        foreach ($memberTraits as $memberTrait) {
            $member = new Member();
            $member
                ->setFirstName($memberTrait['firstName'])
                ->setLastName($memberTrait['lastName'])
                ->setNickName($memberTrait['nickName'])
                ->setPhone($memberTrait['phone'])
                ->setEmail($memberTrait['email'])
                ->setPlainPassword($memberTrait['plainPassword'])
                ->setPassword($memberTrait['plainPassword'])
            ;

            $this->addReference('member_'.$memberTrait['firstName'], $member);

            $manager->persist($member);
        }
        $manager->flush();
    }

    public function getOrder()
    {
        // the order in which fixtures will be loaded
        // the lower the number, the sooner that this fixture is loaded
        return 1;
    }
}
