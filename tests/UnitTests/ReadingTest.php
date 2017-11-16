<?php

namespace Tests\UnitTests;

use ColbyGatte\SmartCsv\Csv;
use ColbyGatte\SmartCsv\CsvUtils;
use ColbyGatte\SmartCsv\Header;
use ColbyGatte\SmartCsv\Iterators\Sip;
use Tests\TestCase;

class ReadingTest extends TestCase
{
    /** @test */
    public function can_sip()
    {
        $names = [];

        $sip = new Sip(__DIR__.'/test.csv');

        foreach ($sip as $row) {
            $names[] = $row->name;
        }

        $this->assertEquals(['Colby', 'Evan', 'Tammy'], $names);
    }

    /** @test */
    public function can_slurp()
    {
        $csv = CsvUtils::slurp(__DIR__.'/test.csv');

        $this->assertCount(3, $csv);
    }

    /** @test */
    public function can_alter()
    {
        CsvUtils::alter(__DIR__.'/test.csv', __DIR__.'/test2.csv', function ($row) {
            if ($row->name == 'Colby') {
                return false;
            }
        });

        $this->assertCount(2, CsvUtils::slurp(__DIR__.'/test2.csv'));
    }

    /** @test */
    public function delete_test()
    {
        $header = new Header(['name', 'age']);
        dump(
            $header->missingColumns(['age', 'occupation'])
        );
    }
}