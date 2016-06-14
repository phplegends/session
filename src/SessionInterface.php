<?php

namespace PHPLegends\Session;

interface SessionInterface
{
    // public function set($key, $value);

    // public function get($key);

    // public function has($key);

    // public function delete($key);

    public function getId();

    public function setId($id);
    
    public function regenerate();

    public function destroy();

    public function save();

    public function gc();

    public function setDriver(DriverInterface $driver);

    /**
     * 
     * @return DriverInterface
     * */
    public function getDriver();

    //public function start();


}