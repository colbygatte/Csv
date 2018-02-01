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
     * @var
     */
    protected $originalHeaderValuesToGroup;

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
     * @param string $groupName
     * @param array  $headerValuesToGroup
     */
    public function makeGroup($groupName, $headerValuesToGroup)
    {
        $this->groupingData[$groupName] = $this->makeGroupingData($headerValuesToGroup);

        $this->originalHeaderValuesToGroup[$groupName] = $headerValuesToGroup;

        $this->findGroupIndexes($groupName);
    }

    /**
     *
     */
    public function reRunGroups()
    {
        $groups = array_keys($this->groupingData);

        array_map([$this, 'findGroupIndexes'], $groups);
    }

    /**
     * @param $groupName
     *
     * @return array
     */
    public function getIndexes($groupName)
    {
        return array_values($this->groups[$groupName]);
    }

    /**
     * @param $groupName
     */
    protected function findGroupIndexes($groupName)
    {
        $groups = [];

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
     * @param $headerValuesToGroup
     *
     * @return array
     */
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

    /**
     * @return mixed
     */
    public function getOriginalHeaderValuesToGroup($groupName)
    {
        return $this->originalHeaderValuesToGroup[$groupName];
    }
}