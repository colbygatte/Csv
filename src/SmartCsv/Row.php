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

    public static function with(Header $header)
    {
        return new static($header);
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
        return array_map([$this, '__get'], $this->header->getValues());
    }

    /**
     * @return array
     */
    public function toDictionary()
    {
        return array_combine($this->header->getValues(), $this->toArray());
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
        return $this->data[$name] ?? null;
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

    public function get($name)
    {
        return $this->__get($name);
    }

    public function set($name, $value)
    {
        return $this->__set($name, $value);
    }

    /**
     * Important: This overwrites any data on the row
     *
     * @param array $data
     */
    public function setUnkeyed($data)
    {
        $this->data = array_combine($this->header->getValues(), $data);

        $this->initKeys();

        return $this;
    }

    /**
     * @param $keyedData
     *
     * @return $this
     */
    public function setKeyed($data)
    {
        $this->data = array_merge($this->data, $data);

        $this->initKeys();

        return $this;
    }

    /**
     * @return \ColbyGatte\SmartCsv\Header
     */
    public function header()
    {
        return $this->header;
    }

    /**
     * @param $groupName
     *
     * @return array
     */
    public function getGroup($name)
    {
        // TODO: unit test this function
        $map = function ($group) {
            $subData = [];

            foreach ($group as $valueName => $index) {
                $subData[$valueName] = $this->data[$this->header->columnForIndex($index)];
            }

            return $subData;
        };

        return array_map($map, $this->header->getGrouper()->getIndexes($name));
    }

    /**
     * Make sure all keys are set.
     *
     * @return void
     */
    protected function initKeys()
    {
        foreach ($this->header->getValues() as $name) {
            if (! isset($this->data[$name])) {
                $this->data[$name] = '';
            }
        }
    }
}
