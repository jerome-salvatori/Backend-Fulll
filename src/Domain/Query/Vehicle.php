<?php

namespace Fulll\Domain\Query;

class Vehicle implements QueryObjectInterface {
    private int $id;
    private string $name;
    private string $plateNumber;

    public function getId(): int {
        return $this->id;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getPlateNumber(): string {
        return $this->plateNumber;
    }
}