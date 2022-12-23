<?php

namespace Fulll\App\Command\Create\Vehicle;

use Fulll\App\Command\CommandInterface;

class Command implements CommandInterface {
    private string $name;
    private string $plateNumber;

    public function __construct(string $name, string $plateNumber) {
        $this->name = $name;
        $this->plateNumber = $plateNumber;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getPlateNumber(): string {
        return $this->plateNumber;
    }
}