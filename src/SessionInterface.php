<?php

namespace PHPLegends\Session;

use PHPLegends\Session\FlashData;
use PHPLegends\Session\GarbageCollector;
use PHPLegends\Session\Handlers\HandlerInterface;

/**
 *
 * @example propostal/example.php
 * */

interface SessionInterface
{

    /**
     * Starts the session. This method must load session storage data and id.
     * This method must be initialize just one time
     * 
     * @return boolean
     * */
    public function start();
    
    /**
     *
     * Sets the id of the session
     * 
     * @param int $id
     * */
    public function setId($id);

    /**
     * Gets the id of the session
     * 
     * @return int
     * */
    public function getId();

    /**
     * Sets the session name
     * 
     * @param string $name
     * @return self
     * */
    public function setName($name);

    /**
     * Gets the session name
     * 
     * 
     * @return string
     * */

    public function getName();

    /**
     * Renegerates a session ID. If 
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
     *
     * @param int|string|\DateTime $lifetime
     * */
    public function setLifetime($lifetime);

    /**
     * Get the lifetime of session
     * 
     * @param int
     * */
    public function getLifetime();

    /**
     * 
     * @return void
     * */
    public function close();

    /**
     * 
     * @return PHPLegends\Session\Storage
     * */
    public function getStorage();

    /**
     * 
     * @param PHPLegends\Session\Storage $storage
     * */
    public function setStorage(Storage $storage);


    /**
     * 
     * @param PHPLegends\Session\FlashData
     * */

    public function setFlashData(FlashData $data);

    /**
     * 
     * @return PHPLegends\Session\FlashData
     * */
    
    public function getFlashData();

    /**
     * 
     * @param \PHPLegends\Session\GarbageCollector $gc
     * @return self
     * */

    public function setGarbageCollector(GarbageCollector $gc);

    /**
     * 
     * @return \PHPLegends\Session\GarbageCollector
     * */
    public function getGarbageCollector();

}
