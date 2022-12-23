<?php

namespace Fulll\Domain\Register;

interface RegisterRepositoryInterface {
    public function findFleet(int $fleetId): ?Fleet;
    public function findVehicle(string $plateNumber): ?Vehicle;
    public function saveFleet(Fleet $fleet): void;
}