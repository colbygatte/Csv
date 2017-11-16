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

    public function toCsv($delimiter = ',')
    {
        ob_start();
        fputcsv(fopen('php://output', 'w'), $this->toAssociativeArray(), $delimiter);
        return ob_get_clean();
    }

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

    public function toAssociativeArray()
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

        return $this;
    }

    public function setKeyedData($keyedData)
    {
        $this->data = array_merge($this->data, $keyedData);

        return $this;
    }

    public function getGroup($groupName)
    {
        $returnData = [];

        $columnGrouper = $this->header->getColumnGrouper();
        $indexGroups = $columnGrouper->getIndexes($groupName);
        $originalValuesToGroup = $columnGrouper->getOriginalHeaderValuesToGroup($groupName);

        foreach ($indexGroups as $indexGroup) {
            $subData = [];

            foreach ($indexGroup as $valueName => $index) {
                $subData[$valueName] = $this->data[$this->header->getColumnForIndex($index)];
            }

            // Make sure all possible keys are initialized.
            foreach ($originalValuesToGroup as $value) {
                if (! isset($subData[$value])) {
                    $subData[$value] = '';
                }
            }

            $returnData[] = $subData;
        }

        return $returnData;
    }

    /**
     * @return \ColbyGatte\CsvMan\Header
     */
    public function getHeader()
    {
        return $this->header;
    }
}