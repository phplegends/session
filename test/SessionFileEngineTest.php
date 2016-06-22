<?php

use PHPLegends\Session\Session;
use PHPLegends\Session\Engines\FileEngine;

class SessionFileEngineTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->sess = new Session(new FileEngine(), 'sess_id_1');
    }

    public function testGet()
    {
        $this->sess->set('name', 'Wallace');

        $this->assertEquals($this->sess->get('name'), 'Wallace');
    }

    public function testSetWhenClosure()
    {
        try {

            $this->sess->set('closure', function ()
            {

            });

        } catch (\Exception $e) {

            $this->assertInstanceOf('\UnexpectedValueException', $e);
        }
    }

    public function testGetId()
    {
        $this->assertEquals('sess_id_1', $this->sess->getId());
    }

    public function testGetEngine()
    {
        $this->assertInstanceOf(
            '\PHPLegends\Session\Engines\EngineInterface',
            $this->sess->getEngine()
        );


    }
}
