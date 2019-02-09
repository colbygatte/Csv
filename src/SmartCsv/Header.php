<?php

namespace ColbyGatte\SmartCsv;

use Countable;
use ColbyGatte\SmartCsv\Helpers\ColumnGrouper;

/**
 * Handles the column header for a CSV
 *
 * @package ColbyGatte\CsvMan
 */
class Header implements Countable
{
    /**
     * @var array
     */
    protected $values;

    /**
     * @var
     */
    protected $valuesFlipped;

    /**
     * @var \ColbyGatte\SmartCsv\Helpers\ColumnGrouper
     */
    protected $grouper;

    /**
     * Header constructor.
     *
     * @param array $header
     */
    public function __construct(array $header = [])
    {
        $this->grouper = new ColumnGrouper($this);

        $this->setValues($header);
    }

    public static function with($header)
    {
        return new static($header);
    }

    /**
     * Return all the columsn that are present in $columns but not in $this->values.
     *
     * @param string[] $columns
     * @return string[]
     */
    public function missingColumns($columns)
    {
        return array_diff($columns, $this->values);
    }

    /**
     * Check if header key exists.
     *
     * @param string $key
     * @return boolean
     */
    public function has($key)
    {
        return isset($this->valuesFlipped[$key]);
    }

    /**
     * @param $column
     *
     * @return null
     */
    public function indexForColumn($column)
    {
        return $this->valuesFlipped[$column] ?? null;
    }

    /**
     * @param $index
     *
     * @return mixed|null
     */
    public function columnForIndex($index)
    {
        return $this->values[$index] ?? null;
    }

    /**
     * @return \ColbyGatte\SmartCsv\Helpers\ColumnGrouper
     */
    public function getGrouper()
    {
        return $this->grouper;
    }

    /**
     * @param       $groupName
     * @param array $headerValuesToGroup
     * 
     * @return $this
     */
    public function makeGroup($groupName, array $values)
    {
        $this->grouper->makeGroup($groupName, $values);

        return $this;
    }

    /**
     * Will always return the header values in the correct order.
     *
     * @return array
     */
    public function getValues()
    {
        return $this->values;
    }

    /**
     * @param $headerValues
     */
    public function setValues($values)
    {
        $this->values = $values;
        $this->valuesFlipped = array_flip($values);

        $this->grouper->runGroups();
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->values);
    }

    /**
     * @param $column
     */
    public function addColumn($column)
    {
        $this->setValues(array_merge(
            $this->values, [$column]
        ));
    }

    public function isNot($header)
    {
        return $header !== $this;
    }
}
