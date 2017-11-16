<?php

namespace ColbyGatte\CsvMan\Iterators;

use ColbyGatte\CsvMan\Header;
use ColbyGatte\CsvMan\Row;

class Sip implements \Iterator
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
     * @var \ColbyGatte\CsvMan\Header
     */
    protected $header;

    protected $currentRow;

    public function __construct($filePath)
    {
        $this->filePath = $filePath;
        $this->initFileHandle();
    }

    /**
     * Return the current element
     *
     * @link  http://php.net/manual/en/iterator.current.php
     * @return \ColbyGatte\CsvMan\Row
     * @since 5.0.0
     */
    public function current()
    {
        return $this->currentRow;
    }

    /**
     * Move forward to next element
     *
     * @link  http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function next()
    {
        if (($data = fgetcsv($this->fileHandle)) !== false) {
            $this->currentRow = new Row($this->header);
            $this->currentRow->setUnkeyedData($data);
        } else {
            $this->currentRow = null;
        }
    }

    /**
     * Return the key of the current element
     *
     * @link  http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     * @since 5.0.0
     */
    public function key()
    {
        return 0;
    }

    /**
     * Checks if current position is valid
     *
     * @link  http://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     * @since 5.0.0
     */
    public function valid()
    {
        return ! ! $this->currentRow;
    }

    /**
     * Rewind the Iterator to the first element
     *
     * @link  http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function rewind()
    {
        $this->initFileHandle();
    }

    protected function initFileHandle()
    {
        $this->fileHandle = fopen($this->filePath, 'r');

        $this->header = new Header(fgetcsv($this->fileHandle));

        $this->next();
    }

    /**
     * @return \ColbyGatte\CsvMan\Header
     */
    public function getHeader()
    {
        return $this->header;
    }
}