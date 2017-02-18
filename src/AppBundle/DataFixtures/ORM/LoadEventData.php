<?php

namespace AppBundle\DataFixtures\ORM;

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

        $description = "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus eget vestibulum risus. 
            Sed porta rutrum sodales. Mauris at volutpat risus, at aliquet risus. Proin rutrum libero aliquet augue 
            consectetur finibus. Integer euismod sagittis ex, at semper sapien."
        ;
        $kip = $this->getReference('member_Kip');
        $me = $this->getReference('member_Ollie');
        $brick = $this->getReference('member_Brick');

        $eventTraits = [
            [
                'name' => 'Haloween 2017',
                'description' => $description,
                'score' => 10,
                'total votes' => null,
                'date' => new \DateTime('2017-10-31'),
                'voting start' => new \DateTime('now'),
                'owner' => $me,
                'estimatedCost' => 100,
            ],
            [
                'name' => 'New Years Eve 2017',
                'description' => 'Lorem Ipsum Etcetera Etcetera. This is a short description.',
                'score' => 7,
                'total votes' => null,
                'date' => new \DateTime('2017-12-31'),
                'voting start' => new \DateTime('now'),
                'owner' => $kip,
                'estimatedCost' => 200,
            ],
            [
                'name' => 'Fly in a friend',
                'description' => $description.'<br /><br />'.$description,
                'score' => 16,
                'total votes' => null,
                'date' => new \DateTime('2017-11-15'),
                'voting start' => new \DateTime('now'),
                'owner' => $brick,
                'estimatedCost' => 700,
            ],
        ];

        foreach ($eventTraits as $eventTrait) {
            $event = new Event();
            $event
                ->setName($eventTrait['name'])
                ->setDescription($eventTrait['description'])
                ->setDate($eventTrait['date'])
                ->setVotingStart($eventTrait['voting start'])
                ->setClub($club)
                ->setOwner($eventTrait['owner'])
                ->setEstimatedCost($eventTrait['estimatedCost'])
            ;
            $endDate = new \DateTime($event->getDate()->format('Y-m-d'));
            $endDate->modify('-1 week');
            $event
                ->setTime($event->getDate()->setTime(14, 55))
                ->setVotingEnd($endDate)
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
