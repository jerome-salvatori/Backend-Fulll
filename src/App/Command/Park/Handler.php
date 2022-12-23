<?php

namespace Fulll\App\Command\Park;

use Fulll\App\Command\HandlerInterface;
use Fulll\App\Command\CommandInterface;
use Fulll\Domain\Park\ParkRepositoryInterface;
use Fulll\Common\VehicleNotFoundException;
use Fulll\Domain\Park\Location;

class Handler implements HandlerInterface {
    private ParkRepositoryInterface $parkRepository;

    public function __construct(ParkRepositoryInterface $parkRepository) {
        $this->parkRepository = $parkRepository;
    }

    public function execute(CommandInterface $command): void {
        $vehicle = $this->parkRepository->findVehicle($command->getPlateNumber());
        if (empty($vehicle)) {
            throw new VehicleNotFoundException("Given plate number didn't match any registered vehicle");
        }
        $locationArray = $command->getLocation();
        if ($locationArray["altitude"]) {
            $location = new Location($locationArray["latitude"], $locationArray["longitude"], $locationArray["altitude"]);
        } else {
            $location = new Location($locationArray["latitude"], $locationArray["longitude"]);
        }
        
        $vehicle->park($location);
        $this->parkRepository->saveVehicle($vehicle);
    }
}