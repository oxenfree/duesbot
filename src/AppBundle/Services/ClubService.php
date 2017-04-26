<?php
/**
 * Created by PhpStorm.
 * User: ollie
 * Date: 3/20/17
 * Time: 7:40 PM
 */

namespace AppBundle\Services;

use AppBundle\Entity\Club;
use AppBundle\Entity\Due;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * Class ClubService
 * @package AppBundle\Services
 */
class ClubService
{
    /**
     * @var EntityManagerInterface $em
     */
    private $em;

    /**
     * ClubService constructor.
     * @param EntityManagerInterface $em
     */
    function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param User $user
     *
     * @return Club
     */
    public function checkClub(User $user)
    {
        $club = $user->getClub();
        if (!isset($club)) {
            $bcEast = $this
                ->em
                ->getRepository(Club::class)
                ->findOneBy(['name' => 'Bat Country East']);
            $user->setClub($bcEast);
        }

        $this->em->persist($user);
        $this->em->flush();

        return $user->getClub();
    }

    /**
     * @param Club $club
     *
     * @return int
     */
    public function checkEventCosts(Club $club)
    {
        $eventCost = 0;
        foreach ($club->getEvents() as $event) {
            if (new \DateTime() <= $event->getDate()) {
                $eventCost += $event->getEstimatedCost();
            }
        }

        return $eventCost;
    }

    /**
     * @param Club $club
     *
     * @return float|int
     */
    public function getCurrentDues(Club $club)
    {
        $dues = $this->em->getRepository(Due::class)->findBy(['club' => $club]);
        $total = 0;

        foreach ($dues as $due) {
            $total += $due->getAmountPerMonth();
        }

        return $total;
    }
}
