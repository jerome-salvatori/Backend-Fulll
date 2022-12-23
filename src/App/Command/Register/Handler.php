<?php

namespace Fulll\App\Command\Register;

use Fulll\App\Command\HandlerInterface;
use Fulll\App\Command\CommandInterface;
use Fulll\Domain\Register\RegisterRepositoryInterface;
use Fulll\Common\VehicleNotFoundException;
use Fulll\Common\FleetNotFoundException;

class Handler implements HandlerInterface {
    private $registerRepository;

    public function __construct(RegisterRepositoryInterface $registerRepository) {
        $this->registerRepository = $registerRepository;
    }

    public function execute(CommandInterface $command): void {
        $vehicle = $this->registerRepository->findVehicle($command->getPlateNumber());
        if (empty($vehicle)) {
            throw new VehicleNotFoundException("Given Plate number doesn't match any known vehicle");
        }
        $fleet = $this->registerRepository->findFleet($command->getFleetId());
        if (empty($fleet)) {
            throw new FleetNotFoundException("Given fleet id doesn't match any known fleet");
        }

        $fleet->register($vehicle);
        $this->registerRepository->saveFleet($fleet);
    }
}