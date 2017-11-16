<?php

namespace ColbyGatte\CsvMan;

class Row
{
    protected $data = [];

    /**
     * @var \ColbyGatte\CsvMan\Header
     */
    protected $header;

    public function __construct(Header $header)
    {
        $this->header = $header;
    }

    /**
     * @return array
     */
    public function toCsvArray()
    {
        $array = [];

        foreach ($this->header->getHeaderValues() as $value) {
            $array[] = $this->$value;
        }

        return $array;
    }

    public function toAssociatedArray()
    {
        return $this->data;
    }

    public function __get($name)
    {
        return isset($this->data[$name]) ? $this->data[$name] : null;
    }

    public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }

    public function setData($keyedData)
    {
        $this->data = $keyedData;

        return $this;
    }
}