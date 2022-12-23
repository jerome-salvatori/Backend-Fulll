<?php

namespace Fulll\App\Command\Park;

use Fulll\App\Command\CommandInterface;

class Command implements CommandInterface {
    private string $plateNumber;
    private array $location;

    public function __construct(string $plateNumber, array $location) {
        $this->plateNumber = $plateNumber;
        $this->location = $location;
    }

    public function getPlateNumber(): string {
        return $this->plateNumber;
    }

    public function getLocation(): array {
        return $this->location;
    }
}