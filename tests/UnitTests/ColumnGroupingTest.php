<?php

namespace Tests\UnitTests;

use ColbyGatte\SmartCsv\Csv;
use ColbyGatte\SmartCsv\Header;
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

        $this->assertEquals(
            [
                ['spec' => 'length', 'val' => '10'],
                ['spec' => 'width', 'val' => '20']
            ],
            $row->getGroup('specs')
        );
    }
}