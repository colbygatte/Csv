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
    public function toString($delimiter = ',')
    {
        ob_start();

        fputcsv(fopen('php://output', 'w'), $this->toDictionary(), $delimiter);

        return ob_get_clean();
    }

    /**
     * @return string
     */
    public function toJson()
    {
        return json_encode($this->toDictionary());
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return array_map([$this, '__get'], $this->header->getHeaderValues());
    }

    /**
     * @return array
     */
    public function toDictionary()
    {
        return $this->data;
    }

    /**
     * Get certain values by key. Default is only used when
     * a key is not in the header.
     *
     * @param [type] $keys
     * @param [type] $default
     * @return void
     */
    public function only($keys, $default = null)
    {
        $data = [];

        foreach ((array) $keys as $key) {
            $data[$key] = $this->header->has($key) ? $this->__get($key) : $default;
        }

        return $data;
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
     * @param $name
     * @return boolean
     */
    public function __isset($name)
    {
        return isset($this->data[$name]);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        try {
            return $this->toString();
        } catch (Exception $e) {
            return '';
        }
    }

    /**
     * Important: This overwrites any data on the row
     *
     * @param $unkeyedData
     */
    public function set($unkeyedData)
    {
        $this->data = array_combine(
            $this->header->getHeaderValues(),
            $unkeyedData
        );

        $this->initKeys();

        return $this;
    }

    /**
     * @param $keyedData
     *
     * @return $this
     */
    public function setKeyed($keyedData)
    {
        $this->data = array_merge($this->data, $keyedData);

        $this->initKeys();

        return $this;
    }

    /**
     * @return \ColbyGatte\SmartCsv\Header
     */
    public function getHeader()
    {
        return $this->header;
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

    protected function initKeys()
    {
        foreach ($this->header->getHeaderValues() as $index => $name) {
            if (! isset($this->data[$name])) {
                $this->data[$name] = '';
            }
        }
    }
}
