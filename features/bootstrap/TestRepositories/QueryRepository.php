<?php

namespace Features\bootstrap\TestRepositories;

use Fulll\Domain\Query\QueryRepositoryInterface;
use Fulll\Infra\Utils\Hydrator;
use Features\bootstrap\Utils\Search;
use Fulll\Domain\Query\User;
use Fulll\Domain\Query\Vehicle;
use Fulll\Domain\Query\Fleet;

class QueryRepository implements QueryRepositoryInterface {
    private Hydrator $hydrator;
    private Search $searchUtil;
    private iterable $vehicles;
    private iterable $fleets;

    public function __construct() {
        $this->hydrator = new Hydrator;
        $this->searchUtil = new Search;
        $this->vehicles = apcu_fetch("vehicle_query") ? apcu_fetch("vehicle_query") : [];
        $this->fleets = apcu_fetch("fleet_query") ? apcu_fetch("fleet_query") : [];
    }

    public function findUser(int $id): ?User {
        //not used
        return null;
    }

    public function findVehicleById(int $id): ?Vehicle {
        //not used
        return null;
    }

    public function findVehicleByPlateNumber(string $plateNumber): ?Vehicle {
        $vehicle = $this->searchUtil->execute($this->vehicles, "plateNumber", $plateNumber);
        if (empty($vehicle)) {
            return null;
        }
        return $this->hydrator->hydrate($vehicle[0]);
    }

    public function findFleetById(int $id): ?Fleet {
        $fleet = $this->searchUtil->execute($this->fleets, "id", $id);
        if (empty($fleet)) {
            return null;
        }
        return $this->hydrator->hydrate($fleet[0]);
    }

    public function findFleetByUser(User $user): ?Fleet {
        //not used
        return null;
    }
}