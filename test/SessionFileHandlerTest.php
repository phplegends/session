<?php

use PHPLegends\Session\Session;
use PHPLegends\Session\Handlers\FileHandler;

class SessionFileHandlerTest extends PHPUnit_Framework_TestCase
{   
    public function tearDown()
    {
        $this->sess->close();
        
        $this->sess = null;

    }
    public function setUp()
    {
        $this->sess = new Session(new FileHandler(), 'sess_id_1');

        $this->sess->start();
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

    public function testGetName()
    {
        $this->assertEquals('sess_id_1', $this->sess->getName());
    }

    public function testGetHandler()
    {
        $this->assertInstanceOf(
            '\PHPLegends\Session\Handlers\HandlerInterface',
            $this->sess->getHandler()
        );
    }
}
