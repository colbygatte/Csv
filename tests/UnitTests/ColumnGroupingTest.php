<?php

namespace Tests\UnitTests;

use ColbyGatte\SmartCsv\Csv;
use ColbyGatte\SmartCsv\Header;
use ColbyGatte\SmartCsv\Row;
use Tests\TestCase;

class ColumnGroupingTest extends TestCase
{
    /** @test */
    public function can_group()
    {
        $header = new Header(['spec1', 'val1', 'spec2', 'val2']);
        $header->makeGroup('specs', ['spec', 'val']);

        $csv = new Csv($header);

        $row = $csv->append(['length', '10', 'width', '20']);

        $this->assertEquals([
            ['spec' => 'length', 'val' => '10'],
            ['spec' => 'width', 'val' => '20']
        ], $row->getGroup('specs'));
    }

    /** @test */
    public function initializes_keys_when_no_data_exists_for_group()
    {
        $row = new Row(new Header(['age1', 'name1', 'age2', 'name2']));
        $row->getHeader()->makeGroup('stuff', ['name', 'age']);
        $row->setKeyedData([
            'age1' => '27',
            'name1' => 'Colby',
        ]);

        $this->assertCount(2, $row->getGroup('stuff'));
    }
}