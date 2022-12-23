<?php

namespace Fulll\Domain\Create;

interface CreateRepositoryInterface {
    public function createUser(string $name): void;
    public function createVehicle(string $name, string $plateNumber): void;
    public function createFleet(int $userId): void;
}