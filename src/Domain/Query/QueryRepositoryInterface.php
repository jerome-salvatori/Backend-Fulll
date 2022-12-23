<?php

namespace Fulll\Domain\Query;

interface QueryRepositoryInterface {
    public function findUser(int $id): ?User;
    public function findVehicleById(int $id): ?Vehicle;
    public function findVehicleByPlateNumber(string $plateNumber): ?Vehicle;
    public function findFleetById(int $id): ?Fleet;
    public function findFleetByUser(User $user): ?Fleet;
}