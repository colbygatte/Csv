<?php

namespace ColbyGatte\SmartCsv\Iterators;

use ColbyGatte\SmartCsv\Header;
use ColbyGatte\SmartCsv\Row;

/**
 * Class Sip
 *
 * @package ColbyGatte\SmartCsv
 */
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
     * @var \ColbyGatte\SmartCsv\Header
     */
    protected $header;

    /**
     * @var \ColbyGatte\SmartCsv\Row
     */
    protected $currentRow;

    /**
     * Sip constructor.
     *
     * @param string $filePath
     */
    public function __construct($filePath)
    {
        $this->filePath = $filePath;
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
        if (($data = fgetcsv($this->fileHandle)) !== false) {
            $this->currentRow = new Row($this->header);
            $this->currentRow->setUnkeyedData($data);
        } else {
            $this->currentRow = null;
        }
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
        $this->fileHandle = fopen($this->filePath, 'r');

        $this->header = new Header(
            fgetcsv($this->fileHandle)
        );

        $this->next();
    }

    /**
     * @return \ColbyGatte\SmartCsv\Header
     */
    public function getHeader()
    {
        return $this->header;
    }
}