<?php

namespace Fulll\Domain\Park;

use Fulll\Domain\Park\Location;
use Fulll\App\Command\Park\AlreadyParkedInLocationException;


class Vehicle {
    private int $id;
    private string $plateNumber;
    private ?Location $location;

    public function park(Location $location): void {
        if ($location == $this->location) {
            throw new AlreadyParkedInLocationException("Vehicle can't be parked twice in the same location");
        }
        $this->location = $location;
    }

    public function getId(): int {
        return $this->id;
    }

    public function getLocation(): ?Location {
        return $this->location;
    }
}