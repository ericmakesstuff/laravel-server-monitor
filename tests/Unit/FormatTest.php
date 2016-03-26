<?php

namespace EricMakesStuff\ServerMonitor\Test\Unit;

use Carbon\Carbon;
use EricMakesStuff\ServerMonitor\Helpers\Format;

class FormatTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function it_can_determine_a_human_readable_filesize()
    {
        $this->assertEquals('10 B', Format::getHumanReadableSize(10));
        $this->assertEquals('100 B', Format::getHumanReadableSize(100));
        $this->assertEquals('1000 B', Format::getHumanReadableSize(1000));
        $this->assertEquals('9.77 KB', Format::getHumanReadableSize(10000));
        $this->assertEquals('976.56 KB', Format::getHumanReadableSize(1000000));
        $this->assertEquals('9.54 MB', Format::getHumanReadableSize(10000000));
        $this->assertEquals('9.31 GB', Format::getHumanReadableSize(10000000000));
    }
}
