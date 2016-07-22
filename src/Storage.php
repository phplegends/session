<?php

namespace PHPLegends\Session;

use PHPLegends\Collections\Collection;

class Storage extends Collection
{
    /**
     * 
     * @param string $key
     * @param mixed $value
     * */
    public function set($key, $value)
    {
        if ($value instanceof \Closure) {

            return $this->set($key, $value());
        }

        return parent::set($key, $value);
    }

}