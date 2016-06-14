<?php

namespace PHPLegends\Session;

abstract class Session implements \Serializable
{
    /**
     * 
     * 
     * */

    public function __construct()
    {
        
    }

    public function setItems(array $items)
    {   
        foreach ($items as $key => $value) {

            $this->set($key, $value);
        }

        return $this;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function regenerate()
    {
        $hash = sha1(time());

        return $this->setId($hash);        
    }

    public function set($key, $value)
    {
        if ($value instanceof \Closure) {

            throw new \UnexpectedValueException(
                "Cannot store Closure(Object) in session"
            );
        }

        $this->items[$key] = $value;

        return $this;
    }

    public function get($key, $default = null)
    {
        return $this->has($key) ? $this->items[$key] : $default;
    }

    public function has($key)
    {
        return isset($this->items[$key]);
    }
}