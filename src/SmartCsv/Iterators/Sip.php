<?php

namespace ColbyGatte\SmartCsv\Iterators;

use ColbyGatte\SmartCsv\Header;
use ColbyGatte\SmartCsv\Row;
use ColbyGatte\SmartCsv\Utils;
use Iterator;

/**
 * Class Sip
 *
 * @package ColbyGatte\SmartCsv
 */
class Sip implements Iterator
{
    /**
     * @var string
     */
    protected $filePath;

    /**
     * @var resource
     */
    protected $fileHandle;

    /**
     * @var \ColbyGatte\SmartCsv\Header
     */
    protected $header;

    /**
     * @var \ColbyGatte\SmartCsv\Row
     */
    protected $currentRow;

    /**
     * @var string
     */
    protected $delimiter;

    /**
     * @var string
     */
    protected $enclosure;

    /**
     * Sip constructor.
     *
     * @param string $filePath
     */
    public function __construct($file)
    {
        $this->file = Utils::file($file);

        $this->initFileHandle();
    }

    /**
     * Return the current element
     *
     * @link  http://php.net/manual/en/iterator.current.php
     * @return \ColbyGatte\SmartCsv\Row
     * @since 5.0.0
     */
    public function current()
    {
        return $this->currentRow;
    }

    /**
     * Move forward to next row
     */
    public function next()
    {
        $this->currentRow = false !== ($data = $this->file->read())
            ? Row::with($this->header)->setUnkeyed($data)
            : null;
    }

    /**
     * Always returns 0
     *
     * @return int
     */
    public function key()
    {
        return 0;
    }

    /**
     * Checks if current position is valid
     *
     * @return boolean The return value will be casted to boolean and then evaluated.
     */
    public function valid()
    {
        return ! ! $this->currentRow;
    }

    /**
     * Rewind the Iterator to the first element
     */
    public function rewind()
    {
        $this->initFileHandle();
    }

    /**
     *
     */
    protected function initFileHandle()
    {
        if ($this->file->isOpen()) {
            $this->file->close();
        }

        $this->file->openRead();

        $this->header = new Header(
            $this->file->read()
        );

        $this->next();
    }

    /**
     * @return \ColbyGatte\SmartCsv\Header
     */
    public function header()
    {
        return $this->header;
    }
}
