<?php

namespace Features\bootstrap\TestRepositories;

use Fulll\Domain\Create\CreateRepositoryInterface;
use Fulll\Domain\Create\User;
use Features\bootstrap\Utils\IdGenerator;
use Fulll\Infra\Utils\Hydrator;
use Features\bootstrap\Utils\Search;

class CreateRepository implements CreateRepositoryInterface {
    private IdGenerator $idGenerator;
    private Hydrator $hydrator;
    private Search $searchUtil;
    private array $users;
    private array $vehicles;
    private array $fleets;

    public function __construct() {
        $this->idGenerator = new IdGenerator;
        $this->hydrator = new Hydrator;
        $this->searchUtil = new Search;
        foreach (["user", "vehicle", "fleet"] as $entityName) {
            $storeKey = $entityName."_create";
            $repoPropName = $entityName."s";
            $storeValue = apcu_fetch($storeKey);
            if (empty($storeValue)) {
                $storeValue = [];
            }
            $this->$repoPropName = $storeValue;
        } 
    }

    public function createUser(string $name): void {
        $id = $this->idGenerator->getEntityNextId("userMaxId");
        $user = ["id" => $id, "name" => $name, "__meta_data__" => "Fulll\Domain\Create\User"];
        $this->users[] = $user;
        apcu_store("user_create", $this->users);
        apcu_store("user_query", $this->users);
    }

    private function findUser(int $id): User {
        $user = $this->searchUtil->execute($this->users, "id", $id);
        return $this->hydrator->hydrate($user[0]);
    }

    public function createVehicle(string $name, string $plateNumber): void {
        $id = $this->idGenerator->getEntityNextId("vehicleMaxId");
        $vehicle = [
            "id" => $id,
            "name" => $name,
            "plateNumber" => $plateNumber,
            "__meta_data__" => "Fulll\Domain\Create\Vehicle"
        ];
        $this->vehicles[] = $vehicle;

        apcu_store("vehicle_create", $this->vehicles);

        $parkVehicle = [
            "id" => $id,
            "plateNumber" => $plateNumber,
            "location" => null,
            "__meta_data__" => "Fulll\Domain\Park\Vehicle"
        ];
        $parkVehicles = apcu_fetch("vehicle_park") ? apcu_fetch("vehicle_park") : [];
        $parkVehicles[] = $parkVehicle;
        apcu_store("vehicle_park", $parkVehicles);

        $registerVehicle = [
            "id" => $id,
            "plateNumber" => $plateNumber,
            "__meta_data__" => "Fulll\Domain\Register\Vehicle"
        ];
        $registerVehicles = apcu_fetch("vehicle_register") ? apcu_fetch("vehicle_register") : [];
        $registerVehicles[] = $registerVehicle;
        apcu_store("vehicle_register", $registerVehicles);

        $queryVehicle = [
            "id" => $id,
            "name" => $name,
            "plateNumber" => $plateNumber,
            "location" => null,
            "__meta_data__" => "Fulll\Domain\Query\Vehicle"
        ];
        $queryVehicles = apcu_fetch("vehicle_query") ? apcu_fetch("vehicle_query") : [];
        $queryVehicles[] = $queryVehicle;
        apcu_store("vehicle_query", $queryVehicles);
    }

    public function createFleet(int $userId): void {
        $user = $this->findUser($userId);
        $user = $this->hydrator->convertObjectToArray($user);
        $id = $this->idGenerator->getEntityNextId("fleetMaxId");
        $fleet = [
            "id" => $id,
            "user" => $user,
            "__meta_data__" => "Fulll\Domain\Create\Vehicle"
        ];
        $this->fleets[] = $fleet;
        apcu_store("fleet_create", $this->fleets);

        $registerFleet = [
            "id" => $id,
            "vehicles" => [],
            "__meta_data__" => "Fulll\Domain\Register\Fleet"
        ];
        $registerFleets = apcu_fetch("fleet_register") ? apcu_fetch("fleet_register") : [];
        $registerFleets[] = $registerFleet;
        apcu_store("fleet_register", $registerFleets);

        $queryFleet = [
            "id" => $id,
            "user" => $user,
            "vehicles" => [],
            "__meta_data__" => "Fulll\Domain\Query\Fleet"
        ];
        $queryFleets = apcu_fetch("fleet_query") ? apcu_fetch("fleet_query") : [];
        $queryFleets[] = $queryFleet;
        apcu_store("fleet_query", $queryFleet);
    }
}