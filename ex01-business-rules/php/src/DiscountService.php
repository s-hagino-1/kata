<?php

namespace Kata\Ex01;

use Kata\Ex01\Model\HighwayDrive;

interface DiscountService
{
    public function calc(HighwayDrive $drive): int;
}
