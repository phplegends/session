<?php

namespace PHPLegends\Session;

class GarbageCollector
{
    /**
     * @var int
     * */
    protected $maxLifetime;

    /**
     * @var int
     * */
    protected $probability;

    /**
     * 
     * @param int $maxLifeTime
     * @param int $probability
     * */
    public function __construct($maxLifetime = 1440, $probability = 1)
    {
        $this->setMaxLifetime($maxLifetime);

        $this->setProbability($probability);
    }

    /**
     * 
     * @return boolean
     * */
    public function passes()
    {
        return mt_rand(1, 100) <= $this->probability;
    }

    /**
     * Gets the value of maxLifetime.
     *
     * @return mixed
     */
    public function getMaxLifetime()
    {
        return $this->maxLifetime;
    }

    /**
     * Sets the value of maxLifetime.
     *
     * @param int|DateTime|string $maxLifetime
     * @return self
     */
    public function setMaxLifetime($maxLifetime)
    {
        if (is_string($maxLifetime) && $stringTime = strtotime($maxLifetime, 0)) {

            $maxLifetime = $stringTime;

        } elseif ($maxLifetime instanceof \DateTime) {

            $maxLifetime = $maxLifetime->format('U') - (new \DateTime)->format('U');

        } elseif (! is_numeric($maxLifetime)) {

            throw new \InvalidArgumentException('Argument for maxLifetime is not valid');
        }

        $this->maxLifetime = $maxLifetime;

        return $this;
    }

    /**
     * Gets the value of probability.
     *
     * @return int
     */
    public function getProbability()
    {
        return $this->probability;
    }

    /**
     * Sets the value of probability.
     *
     * @param int $probability
     *
     * @return self
     */
    public function setProbability($probability)
    {
        if ($probability < 1) {

            throw new \UnexpectedValueException(
                'Probability must be equal or greather than 1'
            );
        }

        $this->probability = $probability;

        return $this;
    }
}