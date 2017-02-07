<?php
/**
 * Created by PhpStorm.
 * User: ollie
 * Date: 2/4/17
 * Time: 9:47 AM
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Due
 * @package AppBundle\Entity
 *
 * @ORM\Table(name="due")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\DueRepository")
 */
class Due
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     *
     * @ORM\JoinColumn(name="user_id", onDelete="CASCADE")
     */
    private $user;

    /**
     * @var Club
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Club")
     */
    private $club;

    /**
     * @var float
     *
     * @Assert\NotBlank()
     *
     * @ORM\Column(name="amount_per_month", type="float")
     */
    private $amountPerMonth;

    /**
     * @var bool
     *
     * @ORM\Column(name="checked_out", type="boolean")
     */
    private $checkedOut;

    /**
     * Due constructor.
     */
    public function __construct()
    {
        if(!isset($this->checkedOut)) {
            $this->checkedOut = false;
        }
    }


    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     *
     * @return Due
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return Club
     */
    public function getClub()
    {
        return $this->club;
    }

    /**
     * @param Club $club
     *
     * @return Due
     */
    public function setClub($club)
    {
        $this->club = $club;
        return $this;
    }

    /**
     * @return float
     */
    public function getAmountPerMonth()
    {
        return $this->amountPerMonth;
    }

    /**
     * @param float $amountPerMonth
     *
     * @return Due
     */
    public function setAmountPerMonth($amountPerMonth)
    {
        $this->amountPerMonth = $amountPerMonth;
        return $this;
    }

    /**
     * @return bool
     */
    public function isCheckedOut()
    {
        return $this->checkedOut;
    }

    /**
     * @param mixed $checkedOut
     *
     * @return Due
     */
    public function setCheckedOut($checkedOut)
    {
        $this->checkedOut = $checkedOut;

        return $this;
    }
}
