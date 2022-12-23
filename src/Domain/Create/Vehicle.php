<?php

namespace Fulll\Domain\Create;



class Vehicle {
    private ?int $id;
    private string $name;
    private string $plateNumber;
    
    public function __construct(string $name, string $plateNumber) {
        $this->name = $name;
        $this->plateNumber = $plateNumber;
    }
}