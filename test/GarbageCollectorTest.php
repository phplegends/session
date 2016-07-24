<?php

use PHPLegends\Session\GarbageCollector;


class GarbageCollectorTest extends PHPUnit_Framework_TestCase
{
    public function testDefaultValues()
    {
        $gc = new GarbageCollector();

        $this->assertEquals(1440, $gc->getMaxLifetime());

        $this->assertEquals(1, $gc->getProbability());
    }

    public function testSetMaxLifeTime()
    {
        $gc = new GarbageCollector();

        $gc->setMaxLifetime('+1 day');

        $this->assertEquals(86400, $gc->getMaxLifetime());

        $gc->setMaxLifetime(new \DateTime('+1 day'));

        $this->assertEquals(86400, $gc->getMaxLifetime());

        $gc->setMaxLifetime(8000);

        $this->assertEquals(8000, $gc->getMaxLifetime());

    }

    public function testSetProbability()
    {
        $gc = new GarbageCollector();

        try {
            $gc->setProbability(-99);

        } catch (\UnexpectedValueException $e) {

            $this->assertEquals(
                'Probability must be equal or greather than 1', $e->getMessage()
            );
        }

        $this->assertEquals(1, $gc->getProbability());

        $gc->setProbability(50);

        $this->assertEquals(50, $gc->getProbability());
    }

}