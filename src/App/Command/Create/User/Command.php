<?php

namespace Fulll\App\Command\Create\User;

use Fulll\App\Command\CommandInterface;

class Command implements CommandInterface {
    private string $name;

    public function __construct(string $name) {
        $this->name = $name;
    }

    public function getName(): string {
        return $this->name;
    }
}