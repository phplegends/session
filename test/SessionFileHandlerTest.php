<?php

use PHPLegends\Session\Session;
use PHPLegends\Session\Handlers\FileHandler;

class SessionFileHandlerTest extends PHPUnit_Framework_TestCase
{   
    public function tearDown()
    {
        @$this->sess->close();
        
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

    public function testArrayAccess()
    {
        $this->assertInstanceOf('\ArrayAccess', $this->sess);

        // offsetset

        $this->sess['name'] = 'Wayne';

        //offsetget

        $this->assertEquals('Wayne', $this->sess['name']);

        //offsetexists

        $this->assertTrue(isset($this->sess['name']));

        //offsetunset

        unset($this->sess['name']);

        // offsetexists

        $this->assertFalse(isset($this->sess['name']));
    }

    public function testHasFlash()
    {
        $this->assertFalse(
            $this->sess->hasFlash('temporary')
        );

        $this->sess->setFlash('temporary', [1, 2, 3]);

        $this->assertTrue($this->sess->hasFlash('temporary'));

        // after 'get' this is removed

        $this->assertEquals(
            [1, 2, 3],
            $this->sess->getFlash('temporary')
        );

        $this->assertFalse($this->sess->hasFlash('temporary'));
    }

    public function testGetFlash()
    {
        $this->sess->setFlash('temporary', [1, 2, 3]);

        $this->assertEquals([1, 2, 3], $this->sess->getFlash('temporary'));

        $this->assertNull($this->sess->getFlash('temporary'));

        $this->assertEquals(
            '__default__', $this->sess->getFlash('temporary', '__default__')
        );

        $this->sess->setFlash('temporary', 5);

        $this->assertEquals(
            5,
            $this->sess->getFlashData()->get('temporary')
        );

        $this->assertNull($this->sess->getFlashData()->get('temporary'));
    }

}
