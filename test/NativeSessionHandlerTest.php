<?php

use PHPLegends\Session\Session;
use PHPLegends\Session\Handlers\NativeSessionHandler;

class NativeSessionHandlerTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->native = new NativeSessionHandler();
    }

    public function testRead()
    {
        $id = 'fake_read_id';

        $_SESSION[$id] = ['name' => 'Wallace'];

        $data = $this->native->read($id);

        $this->assertEquals($_SESSION[$id], $data);
    }

    public function testWrite()
    {
        $id = 'fake_write_id';

        $this->native->write($id, ['name' => 'Wallace']);

        $this->assertEquals($_SESSION[$id]['name'], 'Wallace');
    }

    public function testDestroy()
    {
        $id = 'fake_destroy_id';

        $this->native->write($id, ['x' => 'y']);

        $this->assertTrue(isset($_SESSION[$id]));

        $this->native->destroy($id);

        $this->assertFalse(isset($_SESSION[$id]));
    }
}
