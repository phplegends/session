<?php

namespace PHPLegends\Session;

use PHPLegends\Session\Handlers\HandlerInterface;

/**
 *
 * @author Wallace de Souza <wallacemaxters@gmail.com>
 */
class Session implements SessionInterface
{
	/**
	 *
	 * @var PHPLegends\Session\Storage
	 */
	protected $storage;

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

    protected $closed = false;

    /**
     *  @var string
     * */
    protected $name;

    /**
     * @var string
     * */
    protected $lifetime;

    /**
     * 
     * @param \PHPLegends\Session\Handlers\HandlerInterface $handler
     * @param string $name
     * @param Storage|null $storage
     * */
    public function __construct(
        HandlerInterface $handler, $name = 'PHP_LEGENDS_SESS', Storage $storage = null
    ){
        $this->setHandler($handler);
        $this->setName($name);
        $this->storage = $storage ?: new Storage([]);
    }

    public function start()
    {  
        if ($this->started) {
            return true;
        }

        $this->loadIdFromCookie();        

        $items = $this->getHandler()->read($this->getId());

        $this->storage->setItems($items);

        $this->started = true;
    }

    /**
     *
     * {@inheritDoc}
     * @see \PHPLegends\Session\SessionInterface::getId()
     */
    public function getId()
    {
        return $this->id;
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
     * @see \PHPLegends\Session\SessionInterface::regenerate()
     */
    public function regenerate($destroy = true)
    {
        $id = sha1(time());

        return $this->setId($id);
    }

    /**
     *
     * {@inheritDoc}
     * @see \PHPLegends\Session\SessionInterface::set()
     */
    public function set($key, $value)
    {
        $this->storage->set($key, $value);

        return $this;
    }

    /**
     *
     * {@inheritDoc}
     * @see \PHPLegends\Session\SessionInterface::get()
     */
    public function get($key, $default = null)
    {
        return $this->storage->getOrDefault($key, $default);
    }

    /**
     *
     * {@inheritDoc}
     * @see \PHPLegends\Session\SessionInterface::has()
     */
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
     * Clear the sessions
     * 
     * @return self
     * */
    public function clear()
    {
        $this->storage->clear();

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

    public function close()
    {

        $id = $this->getId();

        $this->getHandler()->write($id, $this->storage->all());

        setcookie($this->getName(), $id, $this->getLifeTime());

        $this->closed = true;
    }

    public function destroy()
    {
    	$id = $this->getId();

    	$this->getHandler()->destroy($id);

        $this->data = [];

        $this->id =  null;

    	return $id;
    }

    public function all()
    {
        return $this->storage->all();
    }

    public function getStorage()
    {
        return $this->storage;
    }

    /**
     *
     * @param int|string|\Datetime $lifetime
     * @return self
     * */
    public function setLifeTime($lifetime)
    {
        if (is_string($lifetime)) {

            $lifetime = strtotime($lifetime);

        } elseif ($lifetime instanceof \DateTime) {

            $lifetime = $lifetime->format('U');

        } elseif (is_int($lifetime)) {

            $lifetime += time();

        } else {

            throw new \InvalidArgumentException('Invalid argument passed');
        }

        $this->lifetime = $lifetime;

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

    protected function generateId()
    {
        return md5(uniqid('_sess'));
    }

    /**
     * 
     * @return void
     * */
    protected function loadIdFromCookie()
    {
        $id = filter_input(INPUT_COOKIE, $this->getName()) ?: $this->generateId();

        $this->setId($id);
    }

}
