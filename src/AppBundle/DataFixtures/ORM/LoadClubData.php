<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Club;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadClubData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $members = [
            $this->getReference('member_Fritz'),
            $this->getReference('member_Frantz'),
            $this->getReference('member_Frank'),
        ];

        $clubTraits = [
            [
                'name' => 'Bat Country East',
                'description' => 'Lorem Ipsum Etcetera Etcetera',
                'treasury' => 100,
            ],
        ];

        foreach ($clubTraits as $clubTrait) {
            $club = new Club();
            $club
                ->setName($clubTrait['name'])
                ->setDescription($clubTrait['description'])
                ->setTreasury($clubTrait['treasury'])
            ;
            foreach ($members as $member)   {
                $club->addMember($member);
            }
            $this->addReference($clubTrait['name'], $club);
            $manager->persist($club);
        }
        $manager->flush();
    }

    public function getOrder()
    {
        // the order in which fixtures will be loaded
        // the lower the number, the sooner that this fixture is loaded
        return 2;
    }
}
