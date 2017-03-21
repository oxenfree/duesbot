<?php
/**
 * Created by PhpStorm.
 * User: ollie
 * Date: 3/20/17
 * Time: 7:56 PM
 */

namespace AppBundle\Services;

use AppBundle\Entity\Due;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class DuesService
 * @package AppBundle\Services
 */
class DuesService
{
    /**
     * @var EntityManagerInterface $em
     */
    private $em;

    /**
     * DuesService constructor.
     * @param EntityManagerInterface $em
     */
    function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param User $user
     * @return Due|null|object
     */
    public function getDuesForUser(User $user)
    {
        return $this
            ->em
            ->getRepository(Due::class)
            ->findOneBy(['user' => $user, 'club' => $user->getClub()])
        ;
    }
}
