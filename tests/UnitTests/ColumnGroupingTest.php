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
        $header = Header::with(['spec1', 'val1', 'spec2', 'val2'])
            ->makeGroup('specs', ['spec', 'val']);

        $row = Csv::with($header)
            ->append(['length', '10', 'width', '20']);

        $this->assertEquals([
            ['spec' => 'length', 'val' => '10'],
            ['spec' => 'width', 'val' => '20'],
        ], $row->getGroup('specs'));
    }

    /** @test */
    public function initializes_keys_when_no_data_exists_for_group()
    {
        $row = Row::with(Header::with(['age1', 'name1', 'age2', 'name2']))
            ->setKeyed([
                'age1' => '27',
                'name1' => 'Colby',
            ]);

        $row->header()->makeGroup('stuff', ['name', 'age']);

        $this->assertEquals(
            [['age' => '27', 'name' => 'Colby'], ['age' => '', 'name' => '']],
            $row->getGroup('stuff')
        );
    }
}
