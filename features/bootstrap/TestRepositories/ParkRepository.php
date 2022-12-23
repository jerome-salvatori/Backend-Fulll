<?php

namespace Features\bootstrap\TestRepositories;

use Fulll\Domain\Park\ParkRepositoryInterface;
use Fulll\Domain\Park\Vehicle;
use Fulll\Infra\Utils\Hydrator;
use Features\bootstrap\Utils\Search;

class ParkRepository implements ParkRepositoryInterface {
    private Hydrator $hydrator;
    private Search $search;
    private array $vehicles;

    public function __construct() {
        $vehicles = apcu_fetch("vehicle_park");
        if (empty($vehicles)) {
            $vehicles = [];
        }
        $this->vehicles = $vehicles;
        $this->searchUtil = new Search;
        $this->hydrator = new Hydrator;
    }

    public function findVehicle(string $plateNumber): ?Vehicle {
        $vehicle = $this->searchUtil->execute($this->vehicles, "plateNumber", $plateNumber);
        if (empty($vehicle)) {
            return null;
        }

        return $this->hydrator->hydrate($vehicle[0]);
    }

    public function saveVehicle(Vehicle $vehicle): void {
        $this->vehicles[] = $this->hydrator->convertObjectToArray($vehicle);
        apcu_store("vehicle_park", $this->vehicles);
    }
}