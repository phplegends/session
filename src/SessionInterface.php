<?php

namespace PHPLegends\Session;

use PHPLegends\Session\Engines\EngineInterface;

/**
 *
 * @example propostal/example.php
 * */

interface SessionInterface
{
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
     *
     * @return void
     *
     * */
    public function regenerate();

    /**
     * Destroy the session
     *
     */
    public function destroy();

    /**
     * Sets the driver
     *
     * @param EngineInterface $driver
     * */
    public function setEngine(EngineInterface $driver);

    /**
     *
     * @return EngineInterface
     * */
    public function getEngine();


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

}
