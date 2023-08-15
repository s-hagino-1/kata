<?php

namespace Kata\Ex01\Model;

use DateTimeInterface;

class HighwayDrive
{
    private ?DateTimeInterface $enteredAt = null;
    private ?DateTimeInterface $exitedAt = null;
    private $vehicleFamily = null;
    private $routeType = null;

    private ?Driver $driver = null;

    public function getEnteredAt(): ?DateTimeInterface
    {
        return $this->enteredAt;
    }

    public function getExitedAt(): ?DateTimeInterface
    {
        return $this->exitedAt;
    }

    public function getVehicleFamily()
    {
        return $this->vehicleFamily;
    }

    public function getRouteType()
    {
        return $this->routeType;
    }

    public function getDriver(): ?Driver
    {
        return $this->driver;
    }

    public function setEnteredAt(?DateTimeInterface $enteredAt): void
    {
        $this->enteredAt = $enteredAt;
    }

    public function setExitedAt(?DateTimeInterface $exitedAt): void
    {
        $this->exitedAt = $exitedAt;
    }

    public function setVehicleFamily($vehicleFamily): void
    {
        $this->vehicleFamily = $vehicleFamily;
    }

    public function setRouteType($routeType): void
    {
        $this->routeType = $routeType;
    }

    public function setDriver(?Driver $driver): void
    {
        $this->driver = $driver;
    }

    public function __toString(): string
    {
        return "HighwayDrive(enteredAt={$this->getEnteredAt()}, exitedAt={$this->getExitedAt()}, vehicleFamily={$this->getVehicleFamily()}, routeType={$this->getRouteType()}, driver={$this->getDriver()})";
    }
}
