<?php

namespace Fulll\App\Command\Create\Fleet;

use Fulll\App\Command\CommandInterface;
use Fulll\App\Domain\Create\User;

class Command implements CommandInterface {
    private int $userId;

    public function __construct(int $userId) {
        $this->userId = $userId;
    }

    public function getUserId(): int {
        return $this->userId;
    }
}