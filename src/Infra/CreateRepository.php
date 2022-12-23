<?php

namespace Fulll\Infra;

use Fulll\Domain\Create\CreateRepositoryInterface;
use Fulll\Infra\Utils\Manager;

class CreateRepository implements CreateRepositoryInterface {
    private Manager $manager;
    
    public function __construct() {
        $this->manager = new Manager;
    }

    public function createUser(string $name): void {
        $sql = "INSERT INTO user (name) VALUES (:name)";
        $req = $this->manager->getDb()->prepare($sql);
        $req->execute(["name" => $name]);
    }

    public function createVehicle(string $name, string $plateNumber): void {
        $sql = "INSERT INTO vehicle (name, plate_number) VALUES (:name, :plate_number)";
        $req = $this->manager->getDb()->prepare($sql);
        $req->execute(["name" => $name, "plate_number" => $plateNumber]);
    }

    public function createFleet(int $userId): void {
        $sql = "INSERT INTO fleet (user_id) VALUES (:user_id)";
        $req = $this->manager->getDb()->prepare($sql);
        $req->execute(["user_id" => $userId]);
        print($this->manager->getDb()->lastInsertId());
    }
}