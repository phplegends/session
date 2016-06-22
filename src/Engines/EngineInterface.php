<?php

namespace PHPLegends\Session\Engines;

/**
 * Interface of possible engines to handle sessions data
 * 
 * @author wallace
 *
 */
interface EngineInterface
{

	/**
	 * 
	 * 
	 * @param mixed $id
	 * @return array
	 */
	public function read($id);
	
	/**
	 * 
	 * @param mixed $id
	 */
    public function destroy($id);
	
    /**
     * 
     * @param int $lifetime
     */
    public function gc($lifetime);
	
    /**
     * 
     * @param mixed $id
     * @param array|\Serializable $data
     */
    public function write($id, $data);
}
