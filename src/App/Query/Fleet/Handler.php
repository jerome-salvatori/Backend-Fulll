<?php

namespace Fulll\App\Query\Fleet;

use Fulll\App\Query\HandlerInterface;
use Fulll\App\Query\QueryInterface;
use Fulll\Domain\Query\QueryRepositoryInterface;
use Fulll\Domain\Query\Fleet;
use Fulll\Common\FleetNotFoundException;

class Handler implements HandlerInterface {
    private QueryRepositoryInterface $queryRepository;

    public function __construct(QueryRepositoryInterface $queryRepository) {
        $this->queryRepository = $queryRepository;
    }

    public function execute(QueryInterface $query): Fleet {
        if ($fleetId = $query->getId()) {
            $fleet = $this->queryRepository->findFleetById($fleetId);
        } elseif ($fleetUser = $query->getUser()) {
            $fleet = $this->queryRepository->findFleetByUser($fleetUser);
        } else {
            throw new \BadMethodCallException("At least either the id or the user of the fleet must be provided");
        }

        if (empty($fleet)) {
            throw new FleetNotFoundException("Given id or user does not match any known fleet");
        }

        return $fleet;
    }
}