<?php
/**
 * Created by PhpStorm.
 * User: ollie
 * Date: 1/30/17
 * Time: 7:26 PM
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EventStatus.
 *
 * @ORM\Table(name="event_status")
 * @ORM\Entity()
 */
class EventStatus
{
    const VOTING_OPEN = "Voting is open.";
    const VOTING_CLOSING = "Voting ends soon.";
    const VOTING_CLOSED = "Voting is closed.";
    const VOTING_WIN = "This is happening!";

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="value", type="string", length=255)
     */
    private $value;

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getValue() ?: '';
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set value.
     *
     * @param string $value
     *
     * @return $this
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value.
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }
}