<?php

namespace Kata\Ex01\Model;

class Driver
{
    private int $countPerMonth;

    public function getCountPerMonth(): int
    {
        return $this->countPerMonth;
    }

    public function setCountPerMonth(int $countPerMonth): void
    {
        $this->countPerMonth = $countPerMonth;
    }

    public function __toString(): string
    {
        return "Driver(countPerMonth={$this->getCountPerMonth()})";
    }
}
