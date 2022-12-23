<?php

namespace Fulll\App\Query\Vehicle;

use Fulll\App\Query\HandlerInterface;
use Fulll\App\Query\QueryInterface;
use Fulll\Domain\Query\QueryRepositoryInterface;
use Fulll\Common\VehicleNotFoundException;
use Fulll\Domain\Query\Vehicle;

class Handler implements HandlerInterface {
    private QueryRepositoryInterface $queryRepository;

    public function __construct(QueryRepositoryInterface $queryRepository) {
        $this->queryRepository = $queryRepository;
    }

    public function execute(QueryInterface $query): Vehicle {
        if ($vehicleId = $query->getId()) {
            $vehicle = $this->queryRepository->findVehicleById($vehicleId);
        } elseif ($plateNumber = $query->getPlateNumber()) {
            $vehicle = $this->queryRepository->findVehicleByPlateNumber($plateNumber);
        } else {
            throw new \BadMethodCallException("At least either the id or the plate number of the vehicle must be provided");
        }

        if (empty($vehicle)) {
            throw new VehicleNotFoundException("Providede id or plate number is not valid");
        }

        return $vehicle;
    }
}