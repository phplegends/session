<?php

namespace PHPLegends\Session;

use PHPLegends\Session\Handlers\HandlerInterface;

/**
 *
 * @example propostal/example.php
 * */

interface SessionInterface
{

    /**
     * @return boolean
     * */
    public function start();
    
    /**
     *
     * @param int $id
     *
     * */
    public function setId($id);

    /**
     *
     * @return int
     * */
    public function getId();

    /**
     * @param string $name
     * @return self
     * */
    public function setName($name);

    /**
     * @return string
     * */

    public function getName();

    /**
     * Renegerates a session ID
     *
     * @param boolean $destroy
     * @return void
     * */
    public function regenerate($destroy = false);

    /**
     * Destroy the session
     *
     */
    public function destroy();

    /**
     * Sets the driver
     *
     * @param HandlerInterface $driver
     * */
    public function setHandler(HandlerInterface $driver);

    /**
     *
     * @return HandlerInterface
     * */
    public function getHandler();


    /**
     * Set's a value in the Session.
     *
     * @param string $key
     * @param mixed $value
     *
     * */

    public function set($key, $value);

    /**
     *
     * @param string $key
     * @return mixed
     * */
    public function get($key);

    /**
     *
     * @param string $key
     * @return mixed
     * */

    public function delete($key);

    /**
     * @param string $key
     * @return boolean
    */
    public function has($key);
    
    /**
     *
     * @return array
     * */

    public function all();

    /**
     *
     * @param int|string|\DateTime $filetime
     * */
    public function setLifetime($lifetime);

    /**
     * Get the filetime of session
     * @param int
     * */
    public function getLifetime();

    /**
     * 
     * @return void
     * */
    public function close();

    /**
     * Clear the session data
     * 
     * */
    public function clear();
}
