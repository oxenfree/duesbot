<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\User;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadUserData extends AbstractFixture implements OrderedFixtureInterface
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
                'userName' => 'Ollie',
                'email' => 'asolivieri@gmail.com',
                'plainPassword' => 'password',
                'role' => 'ROLE_ADMIN'
            ],
            [
                'userName' => 'Kip',
                'email' => 'member@duebat.com',
                'plainPassword' => 'password',
                'role' => 'ROLE_ADMIN'
            ],
            [
                'userName' => 'Brick',
                'email' => 'member2@duebat.com',
                'plainPassword' => 'password',
                'role' => 'ROLE_USER'
            ],

        ];

        foreach ($memberTraits as $memberTrait) {
            $member = new User();
            $member
                ->setUsername($memberTrait['userName'])
                ->setEmail($memberTrait['email'])
                ->setPlainPassword($memberTrait['plainPassword'])
                ->addRole($memberTrait['role'])
                ->setEnabled(true);
            ;

            $this->addReference('member_'.$memberTrait['userName'], $member);

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
