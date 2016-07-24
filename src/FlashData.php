<?php

namespace PHPLegends\Session;


/**
 * 
 * @author Wallace de Souza Vizerra <wallacemaxters@gmail.com>
 * 
 * */
class FlashData extends Storage
{
    /**
     * Retrive value for the key and remove
     * 
     * @param string $key
     * @return mixed
     * */
    public function get($key)
    {
        return $this->delete($key);
    }

    /**
     * Retrive value for the key and remove
     * 
     * @param string $key
     * @param mixed $default
     * @return mixed
     * */
    public function getOrDefault($key, $default = null)
    {
        if ($this->has($key)) {

            return $this->get($key);
        }

        return $default;
    }
}