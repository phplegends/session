<?php

namespace PHPLegends\Session;

use PHPLegends\Session\Engines\EngineInterface;

/**
 *
 * @author Wallace de Souza <wallacemaxters@gmail.com>
 *
 */
class Session implements SessionInterface
{
	/**
	 *
	 * @var array $data
	 */
	protected $data = [];

	/**
	 *
	 * @var string|int
	 */
	protected $id;

	/**
	 *
	 * @param \PHPLegends\Session\EngineInterface $engine
	 * @param string|int mixed $id
	 */
    protected $engine;

    public function __construct(EngineInterface $engine, $id)
    {
        $this->setEngine($engine);

        $this->setId($id);

        $this->data = $this->getEngine()->read($id);
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
    }

    /**
     *
     * {@inheritDoc}
     * @see \PHPLegends\Session\SessionInterface::regenerate()
     */
    public function regenerate()
    {
        $hash = sha1(time());

        return $this->setId($hash);
    }

    /**
     *
     * {@inheritDoc}
     * @see \PHPLegends\Session\SessionInterface::set()
     */
    public function set($key, $value)
    {
        if ($value instanceof \Closure) {

            throw new \UnexpectedValueException(
                "Cannot store Closure(Object) in session"
            );
        }

        $this->data[$key] = $value;

        return $this;
    }

    /**
     *
     * {@inheritDoc}
     * @see \PHPLegends\Session\SessionInterface::get()
     */
    public function get($key, $default = null)
    {
        return $this->has($key) ? $this->data[$key] : $default;
    }

    /**
     *
     * {@inheritDoc}
     * @see \PHPLegends\Session\SessionInterface::has()
     */
    public function has($key)
    {
        return isset($this->data[$key]);
    }

    public function delete($key)
    {
        if (! $this->has($key)) return false;

        $value = $this->get($key);

        unset($this->data[$key]);

        return $value;
    }

    /**
     *
     * {@inheritDoc}
     * @see \PHPLegends\Session\SessionInterface::setEngine()
     */
    public function setEngine(EngineInterface $engine)
    {
    	$this->engine = $engine;
    }

    /**
     *
     * {@inheritDoc}
     * @see \PHPLegends\Session\SessionInterface::getEngine()
     */
    public function getEngine()
    {
    	return $this->engine;
    }


    public function __destruct()
    {
    	$this->getEngine()->write($this->getId(), $this->all());
    }

    public function destroy()
    {
    	$id = $this->getId();

    	$this->getEngine()->destroy($id);

    	$this->setId(null);

    	return $id;
    }

    public function all()
    {
        return $this->data;
    }
}
