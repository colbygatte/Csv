<?php

namespace Tests\UnitTests;

use ColbyGatte\SmartCsv\Utils;
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
        $csv = Utils::slurp(__DIR__.'/test.csv');

        $this->assertCount(3, $csv);
    }

    /** @test */
    public function can_alter()
    {
        Utils::alter(__DIR__.'/test.csv', __DIR__.'/test2.csv', function ($row) {
            if ($row->name == 'Colby') {
                return false;
            }
        });

        $this->assertCount(2, Utils::slurp(__DIR__.'/test2.csv'));
    }
}