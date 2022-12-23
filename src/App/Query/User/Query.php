<?php

namespace Fulll\App\Query\User;

use Fulll\App\Query\QueryInterface;

class Query implements QueryInterface {
    private int $userId;

    public function __construct(int $userId) {
        $this->userId = $userId;
    }

    public function getUserId(): int {
        return $this->userId;
    }
}