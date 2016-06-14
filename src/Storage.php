<?php

namespace PHPLegends\Session;

use PHPLegends\Collections\Collection;

class Storage extends Collection implements \Serializable
{
    /**
     * Store value in storage
     * 
     * @param string|int $key
     * @param mixed $value 
     * */
    public function set($key, $value)
    {
        if ($value instanceof \Closure) {

            throw new \UnexpectedValueException(
                "Cannot store Closure(Object) in session"
            );
        }

        return parent::set($key, $value);
    }

    public function serialize()
    {
        return serialize($this->all());
    }

    public function unserialize($data)
    {
        $this->setItems(unserialize($data));
    }
}