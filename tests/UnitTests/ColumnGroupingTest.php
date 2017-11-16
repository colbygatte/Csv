<?php

namespace Tests\UnitTests;

use ColbyGatte\CsvMan\Csv;
use ColbyGatte\CsvMan\Header;
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
        $data = $row->getGroup('specs');

        $this->assertEquals([
            ['spec' => 'length', 'val' => '10'],
            ['spec' => 'width', 'val' => '20']
        ], $data);
    }
}