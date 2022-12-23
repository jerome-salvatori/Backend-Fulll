<?php

namespace Fulll\Domain\Park;

interface ParkRepositoryInterface {
    public function findVehicle(string $plateNumber): ?Vehicle;
    public function saveVehicle(Vehicle $vehicle): void;
}