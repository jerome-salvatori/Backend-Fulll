<?php

namespace Fulll\Domain\Register;

class Vehicle {
    private int $id;
    private string $plateNumber;

    public function getId(): int {
        return $this->id;
    }
}