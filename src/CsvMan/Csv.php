<?php

namespace ColbyGatte\CsvMan;

class Csv implements \Countable, \Iterator
{
    /**
     * @var \ColbyGatte\CsvMan\Header
     */
    protected $header;

    protected $rows = [];

    public function __construct($header = null)
    {
        if ($header) {
            $this->setHeader($header);
        }
    }

    /**
     * @return \ColbyGatte\CsvMan\Header
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
     * @return \ColbyGatte\CsvMan\Row
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

    public function appendRow(Row $row)
    {
        if ($row->getHeader() !== $this->header) {
            throw new \Exception('Row header and csv header do not match');
        }
        $this->rows[] = $row;
    }

    public function mapRows(callable $callback)
    {
        $data = [];

        foreach ($this as $row) {
            $data[] = $callback($row);
        }

        return $data;
    }

    public function count()
    {
        return count($this->rows);
    }

    public function toJson()
    {
        return json_encode($this->mapRows(function (Row $row) {
            return $row->toAssociativeArray();
        }));
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
        return current($this->rows);
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
        next($this->rows);
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
        return key($this->rows);
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
        return key($this->rows) !== null;
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
        reset($this->rows);
    }

    /**
     * @return \ColbyGatte\CsvMan\Row[]
     */
    public function getRows()
    {
        return $this->rows;
    }

    public function pluckFromColumn($column)
    {
        return $this->mapRows(function ($row) use ($column) {
            return $row->$column;
        });
    }

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