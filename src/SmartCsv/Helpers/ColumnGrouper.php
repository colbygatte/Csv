<?php

namespace ColbyGatte\SmartCsv\Helpers;

use ColbyGatte\SmartCsv\Header;

/**
 * Class ColumnGrouper
 *
 * @package ColbyGatte\SmartCsv
 */
class ColumnGrouper
{
    /**
     * @var \ColbyGatte\SmartCsv\Header
     */
    protected $header;

    /**
     * @var array
     */
    protected $groupingData = [];

    /**
     * @var array
     */
    protected $groups = [];

    /**
     * @var array
     */
    protected $originalValues;

    /**
     * ColumnGrouper constructor.
     *
     * @param \ColbyGatte\SmartCsv\Header $header
     */
    public function __construct(Header $header)
    {
        $this->header = $header;
    }

    /**
     * @param string $name
     * @param array  $headerValuesToGroup
     * @return void
     */
    public function makeGroup($name, $values)
    {
        $this->groupingData[$name] = $this->makeGroupingData($values);

        $this->originalValues[$name] = $values;

        $this->findGroupIndexes($name);
    }

    /**
     * @return void
     */
    public function runGroups()
    {
        $groups = array_keys($this->groupingData);

        array_map([$this, 'findGroupIndexes'], $groups);
    }

    /**
     * @param $name
     *
     * @return array
     */
    public function getIndexes($name)
    {
        return array_values($this->groups[$name]);
    }

    /**
     * @param $name
     */
    protected function findGroupIndexes($name)
    {
        $groups = [];

        foreach ($this->header->getValues() as $index => $value) {
            foreach ($this->groupingData[$name] as $item) {
                if (substr($value, 0, $item['length']) === $item['value']) {
                    $remainder = substr($value, $item['length']);

                    if (! isset($groups[$remainder])) {
                        $groups[$remainder] = [];
                    }
    
                    $groups[$remainder][$item['value']] = $index;
    
                    break;
                }
            }
        }

        $this->groups[$name] = $groups;
    }

    /**
     * @param string[] $values
     *
     * @return array
     */
    protected function makeGroupingData($values)
    {
        return array_map(function ($value) {
            return [
                'value' => $value,
                'length' => strlen($value),
            ];
        }, $values);
    }

    /**
     * @param string $name
     *
     * @return mixed
     */
    public function getOriginalValues($name)
    {
        return $this->originalValues[$name];
    }
}
