<?php

namespace ColbyGatte\SmartCsv;

use Countable;
use Exception;
use Iterator;

/**
 * Class Csv
 *
 * @package ColbyGatte\SmartCsv
 */
class Csv extends Utils implements Countable, Iterator
{
    /**
     * @var \ColbyGatte\SmartCsv\Header
     */
    protected $header;

    /**
     * @var \ColbyGatte\SmartCsv\Row[]
     */
    protected $rows = [];

    /**
     * @param array|\ColbyGatte\SmartCsv\Header $header
     */
    public function __construct($header)
    {
        $this->header = $header instanceof Header ? $header : new Header($header);
    }

    public static function with($header)
    {
        return new static($header);
    }

    /**
     * @return \ColbyGatte\SmartCsv\Header
     */
    public function header()
    {
        return $this->header;
    }

    /**
     * @param array $data
     *
     * @return \ColbyGatte\SmartCsv\Row
     */
    public function append(array $data = [])
    {
        return $this->rows[] = Row::with($this->header)->setUnkeyed(
            array_pad($data, $this->header->count(), '')
        );
    }

    /**
     * @param array $data
     *
     * @return \ColbyGatte\SmartCsv\Row
     */
    public function appendKeyed(array $data)
    {
        return $this->rows[] = Row::with($this->header)->setKeyed($data);
    }

    /**
     * @param \ColbyGatte\SmartCsv\Row $row
     *
     * @throws \Exception
     */
    public function appendRow(Row $row)
    {
        if ($row->header()->isNot($this->header)) {
            throw new Exception('Row header and csv header do not match');
        }

        $this->rows[] = $row;
    }

    /**
     * @param callable $callback
     *
     * @return array
     */
    public function map(callable $callback)
    {
        $data = [];

        foreach ($this as $row) {
            $data[] = $callback($row);
        }

        return $data;
    }

    public function each(callable $callback)
    {
        foreach ($this as $row) {
            if ($callback($row) === false) {
                break;
            }
        }

        return $this;
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->rows);
    }

    /**
     * @return string
     */
    public function toJson()
    {
        return json_encode($this->map(function (Row $row) {
            return $row->toDictionary();
        }));
    }

    /**
     * Return the current row
     *
     * @return \ColbyGatte\SmartCsv\Row
     */
    public function current()
    {
        return current($this->rows);
    }

    /**
     * Move forward to next row
     */
    public function next()
    {
        next($this->rows);
    }

    /**
     * Return the key of the current row
     *
     * @return mixed scalar on success, or null on failure.
     */
    public function key()
    {
        return key($this->rows);
    }

    /**
     * Checks if current position is valid
     *
     * @return boolean The return value will be casted to boolean and then evaluated.
     */
    public function valid()
    {
        return null !== key($this->rows);
    }

    /**
     * Rewind the Iterator to the first element
     */
    public function rewind()
    {
        reset($this->rows);
    }

    /**
     * @return \ColbyGatte\SmartCsv\Row[]
     */
    public function rows()
    {
        return $this->rows;
    }

    /**
     * @param string $column
     *
     * @return array
     */
    public function pluckColumn($column)
    {
        return array_column($this->rows, $column);
    }

    /**
     * @param string[] $columns
     *
     * @return array
     */
    public function pluckColumns($columns)
    {
        return $this->map(function ($row) use ($columns) {
            return array_combine(
                $columns,
                array_map([$row, '__get'], $columns)
            );
        });
    }
}
