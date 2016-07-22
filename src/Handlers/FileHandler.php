<?php

namespace PHPLegends\Session\Handlers;

/**
 * The file handler to store session
 *
 * @author wallace de Souza Vizerra <wallacemaxters@gmail.com>
 */
class FileHandler implements HandlerInterface
{
    /**
     * @var string
     */
    protected $temporaryDirectory;
    
    public function __construct($tempDir = null)
    {
        $tempDir && $this->setTemporaryDirectory($tempDir);
    }

    /**
     *
     * {@inheritDoc}
     * @see \PHPLegends\Session\Engines\EngineInterface::destroy()
     */
    public function destroy($id)
    {
        return @unlink($this->buildFilename($id));
    }

    /**
     *
     * {@inheritDoc}
     * @see \PHPLegends\Session\Engines\EngineInterface::read()
     */
    public function read($id)
    {
        $filename = $this->buildFilename($id);

        if (! file_exists($filename)) {

            touch($filename);
            
            return [];
        }

        return unserialize(file_get_contents($filename));
    }

    /**
     *
     * {@inheritDoc}
     * @see \PHPLegends\Session\Engines\EngineInterface::gc()
     */
    public function gc($lifetime)
    {
        $fileIterator = new \FilesystemIterator($this->getTemporaryDirectory());

        $prefix = $this->getTemporaryFilenamePrefix();

        $callback = function (\SplFileInfo $iterator) use($prefix, $lifetime) {

            return strpos($iterator->getFilename(), $prefix) !== false && $iterator->getMTime() > $lifetime;
        };

        $iterator = new \CallbackFilterIterator($fileIterator, $callback);

        foreach ($iterator as $filename) {
            @unlink($filename);
        }

    }

    /**
     *
     * {@inheritDoc}
     * @see \PHPLegends\Session\Engines\EngineInterface::write()
     */
    public function write($id, $data)
    {
        return @file_put_contents($this->buildFilename($id), serialize($data));
    }

    /**
     * Builds the file name for session
     *
     * @param string $id
     */
    public function buildFilename($id)
    {
        return $this->getTemporaryDirectory() . '/' . 'session__' . $id;
    }

    /**
     *
     * @param string $temporaryDirectory
     * @return self
     */
    public function setTemporaryDirectory($temporaryDirectory)
    {
        $this->temporaryDirectory = $temporaryDirectory;

        return $this;
    }

    /**
     * Gets the temporary directory
     *
     * @return string
     */
    public function getTemporaryDirectory()
    {
        return $this->temporaryDirectory ?: $this->temporaryDirectory = sys_get_temp_dir();
    }
}
