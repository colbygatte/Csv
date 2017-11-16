<?php

namespace ColbyGatte\CsvMan\Helpers;

use ColbyGatte\CsvMan\Header;

class ColumnGrouper
{
    /**
     * @var \ColbyGatte\CsvMan\Header
     */
    protected $header;

    protected $groupingData = [];

    protected $groups = [];

    protected $originalHeaderValuesToGroup;

    public function __construct(Header $header)
    {
        $this->header = $header;
    }

    public function makeGroup($groupName, $headerValuesToGroup)
    {
        $groups = [];

        $this->groupingData[$groupName] = $this->makeGroupingData($headerValuesToGroup);

        $this->originalHeaderValuesToGroup[$groupName] = $headerValuesToGroup;

        $this->findGroupIndexes($groupName);
    }

    public function reRunGroups()
    {
        array_map([$this, 'findGroupIndexes'], /*group names:*/ array_keys($this->groupingData));
    }

    public function getIndexes($groupName)
    {
        return array_values($this->groups[$groupName]);
    }

    protected function makeGroupingData($headerValuesToGroup)
    {
        $data = [];

        $lengths = array_map('strlen', $headerValuesToGroup);
        foreach ($headerValuesToGroup as $index => $headerValueToGroup) {
            $data[] = [
                'length' => $lengths[$index],
                'value' => $headerValueToGroup
            ];
        }

        return $data;
    }

    protected function findGroupIndexes($groupName)
    {
        $groups =  [];

        foreach ($this->header->getHeaderValues() as $entireHeaderIndex => $entireHeaderValue) {
            foreach ($this->groupingData[$groupName] as $groupingDatum) {
                $entireHeaderValueSubstr = substr($entireHeaderValue, 0, $groupingDatum['length']);
                if ($entireHeaderValueSubstr == $groupingDatum['value']) {
                    // The remaining string after the match
                    $remainingSubstr = substr($entireHeaderValue, $groupingDatum['length']);

                    if (! isset($groups[$remainingSubstr])) {
                        $groups[$remainingSubstr] = [];
                    }

                    $groups[$remainingSubstr][$groupingDatum['value']] = $entireHeaderIndex;

                    break;
                }
            }
        }

        $this->groups[$groupName] = $groups;
    }

    /**
     * @return mixed
     */
    public function getOriginalHeaderValuesToGroup($groupName)
    {
        return $this->originalHeaderValuesToGroup[$groupName];
    }
}