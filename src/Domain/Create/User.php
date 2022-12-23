<?php

namespace Fulll\Domain\Create;

class User {
    private ?int $id;
    private string $name;

    public function __construct(string $name) {
        $this->name = $name;
    }
}