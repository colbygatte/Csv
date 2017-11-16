<?php

namespace ColbyGatte\CsvMan;

/**
 * Handles the column header for a CSV
 *
 * @package ColbyGatte\CsvMan
 */
class Header implements \Countable
{
    /**
     * @var array
     */
    protected $headerValues;

    protected $headerValuesFlipped;

    public function __construct(array $header = [])
    {
        $this->setHeaderValues($header);
    }

    public function getIndexForColumn($column)
    {
        return isset($this->headerValuesFlipped[$column]) ? $this->headerValuesFlipped[$column] : false;
    }

    /**
     * Will always return the header values in the correct order.
     *
     * @return array
     */
    public function getHeaderValues()
    {
        return $this->headerValues;
    }

    public function count()
    {
        return count($this->headerValues);
    }

    public function setHeaderValues($headerValues)
    {
        $this->headerValues = $headerValues;
        $this->headerValuesFlipped = array_flip($headerValues);
    }
}