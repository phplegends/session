<?php

namespace PHPLegends\Session\Handlers;

class NativeSessionHandler implements HandlerInterface
{

    public function read($id)
    {
        session_id() || session_start();

        if (isset($_SESSION[$id]))  {
            
            return $_SESSION[$id];
        }

        return $_SESSION[$id] = [];
    }

    public function write($id, $data)
    {
        $_SESSION[$id] = $data;
    }

    public function gc($lifetime) {}

    public function destroy($id)
    {
        unset($_SESSION[$id]);
    }
}