<?php

namespace Fulll\App\Query\Vehicle;

use Fulll\App\Query\QueryInterface;

class Query implements QueryInterface {
    private ?int $id;
    private ?string $plateNumber;

    public function __construct(?int $id, ?string $plateNumber = null) {
        $this->id = $id;
        $this->plateNumber = $plateNumber;
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function getPlateNumber(): ?string {
        return $this->plateNumber;
    }
}