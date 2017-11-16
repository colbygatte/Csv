<?php

namespace ColbyGatte\SmartCsv;

/**
 * Class Csv
 *
 * @package ColbyGatte\SmartCsv
 */
class Csv implements \Countable, \Iterator
{
    /**
     * @var \ColbyGatte\SmartCsv\Header
     */
    protected $header;

    /**
     * @var \ColbyGatte\SmartCsv\Row[]
     */
    protected $rows = [];

    public function __construct($header = null)
    {
        if ($header) {
            $this->setHeader($header);
        }
    }

    /**
     * @return \ColbyGatte\SmartCsv\Header
     */
    public function getHeader()
    {
        return $this->header;
    }

    /**
     * @param array $header
     */
    public function setHeader($header)
    {
        $this->header = $header instanceof Header ? $header : new Header($header);

        return $this;
    }

    /**
     * @param array $data
     *
     * @return \ColbyGatte\SmartCsv\Row
     */
    public function append(array $data)
    {
        if (count($data) != count($this->header)) {
            $data = array_pad($data, count($this->header), '');
        }

        $row = (new Row($this->header))->setUnkeyedData($data);

        $this->rows[] = $row;

        return $row;
    }

    /**
     * @param \ColbyGatte\SmartCsv\Row $row
     *
     * @throws \Exception
     */
    public function appendRow(Row $row)
    {
        if ($row->getHeader() !== $this->header) {
            throw new \Exception('Row header and csv header do not match');
        }
        $this->rows[] = $row;
    }

    /**
     * @param callable $callback
     *
     * @return array
     */
    public function mapRows(callable $callback)
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
            $callback($row);
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
        return json_encode($this->mapRows(function (Row $row) {
            return $row->toAssociativeArray();
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
        return key($this->rows) !== null;
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
    public function getRows()
    {
        return $this->rows;
    }

    /**
     * @param string $column
     *
     * @return array
     */
    public function pluckFromColumn($column)
    {
        return $this->mapRows(function ($row) use ($column) {
            return $row->$column;
        });
    }

    /**
     * @param string[] $columns
     *
     * @return array
     */
    public function pluckFromColumns($columns)
    {
        return $this->mapRows(function ($row) use ($columns) {
            $data = [];

            foreach ($columns as $column) {
                $data[$column] = $row->$column;
            }

            return $data;
        });
    }
}