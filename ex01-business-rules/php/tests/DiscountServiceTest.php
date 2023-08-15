<?php

namespace Tests\Kata\Ex01;

use Kata\Ex01\Model\Driver;
use Kata\Ex01\Model\HighwayDrive;
use PHPUnit\Framework\TestCase;
use Kata\Ex01\DiscountService;
use Kata\Ex01\DiscountServiceImpl;
use Kata\Ex01\Model\RouteType;
use Kata\Ex01\Model\VehicleFamily;

class DiscountServiceTest extends TestCase
{
    private DiscountService $discountService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->discountService = new DiscountServiceImpl();
    }

    private function driver(int $usingCount): Driver
    {
        $driver = new Driver();
        $driver->setCountPerMonth($usingCount);
        return $driver;
    }

    public function test平日朝夕割引(): void
    {
        $drive = new HighwayDrive();
        $drive->setEnteredAt(new \DateTime('2016-03-31 23:00'));
        $drive->setExitedAt(new \DateTime('2016-04-01 06:30'));
        $drive->setDriver($this->driver(10));
        $drive->setVehicleFamily(VehicleFamily::STANDARD);
        $drive->setRouteType(RouteType::RURAL);

        $this->assertEquals(50, $this->discountService->calc($drive));
    }

    public function test休日朝夕は休日割が適用される(): void
    {
        $drive = new HighwayDrive();
        $drive->setEnteredAt(new \DateTime('2016-04-01 23:00'));
        $drive->setExitedAt(new \DateTime('2016-04-02 06:30'));
        $drive->setDriver($this->driver(10));
        $drive->setVehicleFamily(VehicleFamily::STANDARD);
        $drive->setRouteType(RouteType::RURAL);

        $this->assertEquals(30, $this->discountService->calc($drive));
    }
}
