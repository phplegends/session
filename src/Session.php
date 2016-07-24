<?php

namespace PHPLegends\Session;

use PHPLegends\Session\SessionInterface;
use PHPLegends\Session\Handlers\HandlerInterface;

/**
 *
 * @author Wallace de Souza <wallacemaxters@gmail.com>
 */
class Session implements SessionInterface, \ArrayAccess
{

    const KEY_STORAGE = 'storage';

    const KEY_FLASH_DATA = 'flash_data';

	/**
	 *
	 * @var PHPLegends\Session\Storage
	 */
	protected $storage;

    /**
     * 
     * @var PHPLegends\Session\FlashData
     * */
    protected $flash;

	/**
	 *
	 * @var string|int
	 */
	protected $id;

	/**
	 *
	 * @param \PHPLegends\Session\HandlerInterface $Handler
	 * @param string|int mixed $id
	 */
    protected $handler;

    /**
     * 
     * @var boolean
     * */
    protected $started = false;

    /**
     * 
     * @var boolean
     * */
    protected $closed = false;

    /**
     *  @var string
     * */
    protected $name;

    /**
     * @var string
     * */
    protected $lifetime = 0;

    /**
     * 
     * @param \PHPLegends\Session\Handlers\HandlerInterface $handler
     * @param string $name
     * @param Storage|null $storage
     * @param FlashData|null $flash
     * */
    public function __construct(
        HandlerInterface $handler,
        $name = 'PHP_LEGENDS_SESS',
        Storage $storage = null,
        FlashData $flash = null
    ){
        $this->setHandler($handler);

        $this->setName($name);

        $this->setStorage($storage ?: new Storage);

        $this->setFlashData($flash ?: new FlashData);

        $this->start();
    }

    /**
     * 
     * {@inheritDoc}
     * @see \PHPLegends\Session\SessionInterface::start()
     * */
    public function start()
    {  
        if ($this->started) {

            return true;
        }

        $this->id = filter_input(INPUT_COOKIE, $this->getName());

        $this->read();

        $this->started = true;
    }

    /**
     *
     * {@inheritDoc}
     * @see \PHPLegends\Session\SessionInterface::setId()
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     *
     * {@inheritDoc}
     * @see \PHPLegends\Session\SessionInterface::getId()
     */
    public function getId()
    {
        return $this->id ?: $this->regenerate()->id;
    }

    /**
     *
     * {@inheritDoc}
     * @see \PHPLegends\Session\SessionInterface::regenerate()
     */
    public function regenerate($destroy = false)
    {
        $destroy && $this->getHandler()->destroy($this->id);

        $this->id = sha1(uniqid('_sess', true));

        return $this;
    }

    public function set($key, $value)
    {
        $this->storage->set($key, $value);

        return $this;
    }

    public function get($key, $default = null)
    {
        return $this->storage->getOrDefault($key, $default);
    }

    public function has($key)
    {
        return $this->storage->has($key);
    }

    public function delete($key)
    {
        if (! $this->has($key)) return false;

        $value = $this->storage->get($key);

        unset($this->storage[$key]);

        return $value;
    }

    /**
     * 
     * @param string $key
     * @return boolean
     * */
    public function hasFlash($key)
    {
        return $this->flash->has($key);
    }

    /**
     * 
     * @param string $key
     * @param mixed $default
     * @return mixed
     * */
    public function getFlash($key, $default = null)
    {
        return $this->flash->getOrDefault($key, $default);
    }

    /**
     * 
     * @param string $key
     * @param mixed $value
     * @return flash
     * */
    public function setFlash($key, $value)
    {
        $this->flash->set($key, $value);

        return $this;
    }

    /**
     * Clear the sessions
     * 
     * @return self
     * */
    public function clear()
    {
        $this->storage->clear();

        $this->flash->clear();

        return $this;
    }

    /**
     *
     * {@inheritDoc}
     * @see \PHPLegends\Session\SessionInterface::setHandler()
     */
    public function setHandler(HandlerInterface $handler)
    {
    	$this->handler = $handler;
    }

    /**
     *
     * {@inheritDoc}
     * @see \PHPLegends\Session\SessionInterface::getHandler()
     */
    public function getHandler()
    {
    	return $this->handler;
    }

    public function __destruct()
    {
        $this->closed || $this->close();
    }

    /**
     *
     * {@inheritDoc}
     * @see \PHPLegends\Session\SessionInterface::close()
     */
    public function close()
    {
        $this->write();

        if ($this->lifetime > 0) {

            setcookie($this->getName(), $this->getId(), $this->lifetime + time());

        } else {

            setcookie($this->getName(), $this->getId());
        }

        $this->getHandler()->gc($this->lifetime);

        $this->closed = true;
    }

    /**
     *
     * {@inheritDoc}
     * @see \PHPLegends\Session\SessionInterface::destroy()
     */
    public function destroy()
    {
    	$id = $this->getId();

    	$this->getHandler()->destroy($id);

        $this->id = null;

    	return $id;
    }

    public function all()
    {
        return $this->storage->all();
    }

    /**
     *
     * {@inheritDoc}
     * @see \PHPLegends\Session\SessionInterface::getStorage()
     */
    public function getStorage()
    {
        return $this->storage;
    }

    /**
     *
     * {@inheritDoc}
     * @see \PHPLegends\Session\SessionInterface::setStorage()
     */
    public function setStorage(Storage $storage)
    {
        $this->storage = $storage;

        return $this;
    }

    /**
     *
     * {@inheritDoc}
     * @see \PHPLegends\Session\SessionInterface::setFlashData()
     */
    public function setFlashData(FlashData $flash)
    {
        $this->flash = $flash;

        return $this;
    }


    /**
     *
     * {@inheritDoc}
     * @see \PHPLegends\Session\SessionInterface::getFlashData()
     */
    public function getFlashData()
    {
        return $this->flash;
    }

    /**
     *
     * @param int|string|\Datetime $lifetime
     * @return self
     * */
    public function setLifeTime($lifetime)
    {
        if (is_string($lifetime)) {

            $lifetime = strtotime($lifetime, 0);

        } elseif ($lifetime instanceof \DateTime) {

            $lifetime = $lifetime->format('U') - time();

        } elseif (! is_int($lifetime)) {

            throw new \InvalidArgumentException(
                'The lifetime argument must be int, time string or DateTime Object'
            );
        }

        // The session lifetime must be greather or equal than 0

        $this->lifetime = max(0, $lifetime);

        return $this;
    }

    public function getLifeTime()
    {
        return $this->lifetime;
    }

    public function getLifetimeAsDateTime()
    {
        return new \DateTime($this->lifetime);
    }

    public function setName($name)
    {
        $this->name = $name;
    }
     
    public function getName()
    {
        return $this->name;
    }

    /**
     * Easy way to write data in handler
     * 
     * @return void
     * */
    public function write()
    {
        $data = [
            static::KEY_STORAGE    => $this->storage->all(),
            static::KEY_FLASH_DATA => $this->flash->all()
        ];

        return $this->getHandler()->write($this->getId(), $data);
    }

    /**
     * Easy way to retrieve data
     * 
     * @return array
     * */
    public function read()
    {
        $data = $this->getHandler()->read($this->getId());

        $data += [
            static::KEY_STORAGE    => [],
            static::KEY_FLASH_DATA => []
        ];

        $this->storage->setItems($data[static::KEY_STORAGE]);

        $this->flash->setItems($data[static::KEY_FLASH_DATA]);

        return $data;
    }

    public function offsetGet($key)
    {
        return $this->get($key);
    }    

    public function offsetSet($key, $value)
    {
        $this->set($key, $value);
    }

    public function offsetExists($key)
    {
        return $this->has($key);
    }

    public function offsetUnset($key)
    {
        $this->delete($key);
    }
}
