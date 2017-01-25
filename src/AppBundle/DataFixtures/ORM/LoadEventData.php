<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Club;
use AppBundle\Entity\Event;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadEventData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $club = $this->getReference('Bat Country East');

        $eventTraits = [
            [
                'name' => 'Haloween 2017',
                'description' => 'Lorem Ipsum Etcetera Etcetera',
                'score' => null,
                'total votes' => null,
                'date' => new \DateTime(),
                'voting start' => new \DateTime('now'),
            ],
            [
                'name' => 'New Years Eve 2017',
                'description' => 'Lorem Ipsum Etcetera Etcetera',
                'score' => null,
                'total votes' => null,
                'date' => new \DateTime(),
                'voting start' => new \DateTime('now'),
            ],
            [
                'name' => 'Fly in a friend',
                'description' => 'Lorem Ipsum Etcetera Etcetera',
                'score' => null,
                'total votes' => null,
                'date' => new \DateTime(),
                'voting start' => new \DateTime('now'),
            ],
        ];

        foreach ($eventTraits as $eventTrait) {
            $event = new Event();
            $event
                ->setName($eventTrait['name'])
                ->setDescription($eventTrait['description'])
                ->setScore($eventTrait['score'])
                ->setTotalVotes($eventTrait['total votes'])
                ->setDate($eventTrait['date'])
                ->setVotingStart($eventTrait['voting start'])
                ->setClub($club)
            ;
            $event
                ->setTime($event->getDate()->setTime(14, 55))
                ->setVotingEnd($event->getDate()->add(new \DateInterval('P30D')))
                ->setDate($event->getDate()->setDate(2017, 11, 31))
            ;

            $this->addReference($eventTrait['name'], $event);
            $manager->persist($event);
        }
        $manager->flush();
    }

    public function getOrder()
    {
        // the order in which fixtures will be loaded
        // the lower the number, the sooner that this fixture is loaded
        return 3;
    }
}
