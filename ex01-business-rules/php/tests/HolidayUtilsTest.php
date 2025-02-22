<?php

namespace Kata\Ex01;

use PHPUnit\Framework\TestCase;
use Kata\Ex01\Util\HolidayUtils;
use DateTime;

class HolidayUtilsTest extends TestCase
{
    public function test元日は休日(): void
    {
        $holidayUtils = new HolidayUtils();
        $this->assertTrue($holidayUtils->isHoliday(new DateTime('2024-01-01')));
        $this->assertTrue($holidayUtils->isHoliday(new DateTime('2024-01-08')));
        $this->assertFalse($holidayUtils->isHoliday(new DateTime('2024-01-04')));
    }
}
