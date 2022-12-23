<?php

namespace Fulll\App\Command\Register;

use Fulll\App\Command\CommandInterface;

class Command implements CommandInterface {
    private int $fleetId;
    private string $plateNumber;

    public function __construct(int $fleetId, string $plateNumber) {
        $this->fleetId = $fleetId;
        $this->plateNumber = $plateNumber;
    }

    public function getFleetId(): int {
        return $this->fleetId;
    }

    public function getPlateNumber(): string {
        return $this->plateNumber;
    }
}