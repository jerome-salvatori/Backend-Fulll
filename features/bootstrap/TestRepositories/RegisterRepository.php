<?php

namespace Features\bootstrap\TestRepositories;

use Fulll\Domain\Register\RegisterRepositoryInterface;
use Fulll\Domain\Register\Fleet;
use Fulll\Domain\Register\Vehicle;
use Features\bootstrap\Utils\Search;
use Fulll\Infra\Utils\Hydrator;

class RegisterRepository implements RegisterRepositoryInterface {
    private array $fleets;
    private array $vehicles;
    private Search $searchUtil;
    private Hydrator $hydrator;

    public function __construct() {
        $fleets = apcu_fetch("fleet_register");
        if (empty($fleets)) {
            $fleets = [];
        }
        $this->fleets = $fleets;

        $vehicles = apcu_fetch("vehicle_register");
        if (empty($vehicles)) {
            $vehicles = [];
        }
        $this->vehicles = $vehicles;
        $this->searchUtil = new Search;
        $this->hydrator = new Hydrator;
    }

    public function findFleet(int $fleetId): ?Fleet {
        $fleet = $this->searchUtil->execute($this->fleets, "id", $fleetId);
        if (empty($fleet)) {
            return null;
        }

        return $this->hydrator->hydrate($fleet[0]);
    }

    public function findVehicle(string $plateNumber): ?Vehicle {
        $vehicle = $this->searchUtil->execute($this->vehicles, "plateNumber", $fleetId);
        if (empty($vehicle)) {
            return null;
        }

        return $this->hydrator->hydrate($vehicle[0]);
    }

    public function saveFleet(Fleet $fleet): void {
        $this->fleets[] = $fleetArray = $this->hydrator->convertObjectToArray($fleet);
        apcu_store("fleet_register", $this->fleets);
        $queryFleets = apcu_fetch("fleet_query");
        $queryFleet = $queryFleetUpdated = $this->searchUtil($queryFleets, "id", $fleetArray["id"]);
        $queryFleetUpdated["vehicles"] = $fleetArray["vehicles"];
        $queryFleetKey = array_search($queryFleet, $queryFleets);
        $queryFleets[$queryFleetKey] = $queryFleetUpdated;
        apcu_store("fleet_query", $queryFleets);
    }
}