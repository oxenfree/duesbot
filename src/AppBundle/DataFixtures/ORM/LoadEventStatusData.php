<?php
/**
 * Created by PhpStorm.
 * User: ollie
 * Date: 1/30/17
 * Time: 7:47 PM
 */

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\EventStatus;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadEventStatusData extends AbstractFixture implements OrderedFixtureInterface
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

        $open = (new EventStatus())
            ->setValue(EventStatus::VOTING_OPEN);

        $manager->persist($open);

        $closed = (new EventStatus())
            ->setValue(EventStatus::VOTING_CLOSED);

        $manager->persist($closed);

        $win = (new EventStatus())
            ->setValue(EventStatus::VOTING_WIN);

        $manager->persist($win);

        $halloween->setStatus($open);
        $newYear->setStatus($closed);
        $flyFriend->setStatus($win);

        $manager->flush();
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 5;
    }
}