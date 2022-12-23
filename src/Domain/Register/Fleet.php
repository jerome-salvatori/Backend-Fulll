<?php

namespace Fulll\Domain\Register;

use Fulll\Domain\Register\Vehicle;
use Fulll\App\Command\Register\VehicleAlreadyInFleetException;

class Fleet {
    private int $id;
    private iterable $vehicles;

    public function register(Vehicle $vehicle): void {
        if ($this->vehicleContains($vehicle)) {
            throw new \VehicleAlreadyInFleetException("The same vehicle can't be registered twice in a fleet");
        }

        $this->vehicles[] = $vehicle;
    }

    private function vehicleContains(Vehicle $vehicle): bool {
        if (is_object($this->vehicles) && method_exists($this->vehicles, 'contains')) {
            return $this->vehicles->contains($vehicle);
        }
        return in_array($vehicle, $this->vehicles);
    }

    public function getVehicles(): iterable {
        return $this->vehicles;
    }

    public function getId(): int {
        return $this->id;
    }
}