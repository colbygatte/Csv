<?php

namespace ColbyGatte\SmartCsv;

/**
 * Class Row
 *
 * @package ColbyGatte\SmartCsv
 */
class Row
{
    /**
     * @var array
     */
    protected $data = [];

    /**
     * @var \ColbyGatte\SmartCsv\Header
     */
    protected $header;

    /**
     * Row constructor.
     *
     * @param \ColbyGatte\SmartCsv\Header $header
     */
    public function __construct(Header $header)
    {
        $this->header = $header;
    }

    /**
     * @param string $delimiter
     *
     * @return string
     */
    public function toCsv($delimiter = ',')
    {
        ob_start();
        fputcsv(fopen('php://output', 'w'), $this->toAssociativeArray(), $delimiter);
        return ob_get_clean();
    }

    /**
     * @return string
     */
    public function toJson()
    {
        return json_encode($this->toAssociativeArray());
    }

    /**
     * @return array
     */
    public function toCsvArray()
    {
        $array = [];

        foreach ($this->header->getHeaderValues() as $value) {
            $array[] = $this->__get($value);
        }

        return $array;
    }

    /**
     * @return array
     */
    public function toAssociativeArray()
    {
        return $this->data;
    }

    /**
     * @param $name
     *
     * @return string|null
     */
    public function __get($name)
    {
        return isset($this->data[$name]) ? $this->data[$name] : null;
    }

    /**
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }

    /**
     * Important: This overwrites any data on the row
     *
     * @param $unkeyedData
     */
    public function setUnkeyedData($unkeyedData)
    {
        $this->data = array_combine(
            $this->header->getHeaderValues(), $unkeyedData
        );

        $this->initializeAllKeys();

        return $this;
    }

    /**
     * @param $keyedData
     *
     * @return $this
     */
    public function setKeyedData($keyedData)
    {
        $this->data = array_merge($this->data, $keyedData);

        $this->initializeAllKeys();

        return $this;
    }

    protected function initializeAllKeys()
    {
        foreach ($this->header->getHeaderValues() as $index => $name) {
            if (! isset($this->data[$name])) {
                $this->data[$name] = '';
            }
        }
    }

    /**
     * @param $groupName
     *
     * @return array
     */
    public function getGroup($groupName)
    {
        $returnData = [];

        $columnGrouper = $this->header->getColumnGrouper();
        $indexGroups = $columnGrouper->getIndexes($groupName);

        foreach ($indexGroups as $indexGroup) {
            $subData = [];

            foreach ($indexGroup as $valueName => $index) {
                $subData[$valueName] = $this->data[$this->header->getColumnForIndex($index)];
            }

            $returnData[] = $subData;
        }

        return $returnData;
    }

    /**
     * @return \ColbyGatte\SmartCsv\Header
     */
    public function getHeader()
    {
        return $this->header;
    }
}