<?php

namespace RedmineBundle\Entity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Tracker
 */
class Tracker
{
    /**
     * @Assert\Type(
     *     type="integer",
     *     message="The value {{ value }} is not a valid {{ type }}."
     * )
     * @var int
     */
    private $time;


    /**
     * Set time
     *
     * @param integer $time
     *
     * @return Tracker
     */
    public function setTime($time)
    {
        $this->time = $time;

        return $this;
    }

    /**
     * Get time
     *
     * @return int
     */
    public function getTime()
    {
        return $this->time;
    }
}

