<?php

namespace ColbyGatte\SmartCsv;

use ColbyGatte\SmartCsv\Helpers\ColumnGrouper;

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

    /**
     * @var
     */
    protected $headerValuesFlipped;

    /**
     * @var \ColbyGatte\SmartCsv\Helpers\ColumnGrouper
     */
    protected $columnGrouper;

    /**
     * Header constructor.
     *
     * @param array $header
     */
    public function __construct(array $header = [])
    {
        $this->columnGrouper = new ColumnGrouper($this);
        $this->setHeaderValues($header);
    }

    /**
     * @param $column
     *
     * @return null
     */
    public function getIndexForColumn($column)
    {
        return isset($this->headerValuesFlipped[$column]) ? $this->headerValuesFlipped[$column] : null;
    }

    /**
     * @param $index
     *
     * @return mixed|null
     */
    public function getColumnForIndex($index)
    {
        return isset($this->headerValues[$index]) ? $this->headerValues[$index] : null;
    }

    /**
     * @return \ColbyGatte\SmartCsv\Helpers\ColumnGrouper
     */
    public function getColumnGrouper()
    {
        return $this->columnGrouper;
    }

    /**
     * @param       $groupName
     * @param array $headerValuesToGroup
     */
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

    /**
     * @param $headerValues
     */
    public function setHeaderValues($headerValues)
    {
        $this->headerValues = $headerValues;
        $this->headerValuesFlipped = array_flip($headerValues);

        $this->columnGrouper->reRunGroups();
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->headerValues);
    }

    /**
     * @param $column
     */
    public function addColumn($column)
    {
        $headerValues = $this->headerValues;
        $headerValues[] = $column;

        $this->setHeaderValues($headerValues);
    }
}