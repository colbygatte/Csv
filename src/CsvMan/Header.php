<?php

namespace ColbyGatte\CsvMan;

use ColbyGatte\CsvMan\Helpers\ColumnGrouper;

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

    protected $columnGrouper;

    public function __construct(array $header = [])
    {
        $this->columnGrouper = new ColumnGrouper($this);
        $this->setHeaderValues($header);
    }

    public function getIndexForColumn($column)
    {
        return isset($this->headerValuesFlipped[$column]) ? $this->headerValuesFlipped[$column] : null;
    }

    public function getColumnForIndex($index)
    {
        return isset($this->headerValues[$index]) ? $this->headerValues[$index] : null;
    }

    public function getColumnGrouper()
    {
        return $this->columnGrouper;
    }

    public function makeGroup($groupName, array $headerValuesToGroup)
    {
        $this->columnGrouper->makeGroup($groupName, $headerValuesToGroup);
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

        $this->columnGrouper->reRunGroups();
    }

    public function addColumn($column)
    {
        $headerValues = $this->headerValues;
        $headerValues[] = $column;

        $this->setHeaderValues($headerValues);
    }
}