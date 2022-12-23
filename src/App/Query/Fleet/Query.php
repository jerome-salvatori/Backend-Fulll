<?php

namespace Fulll\App\Query\Fleet;

use Fulll\App\Query\QueryInterface;
use Fulll\Domain\Query\User;

class Query implements QueryInterface {
    private ?int $id;
    private ?User $user;

    public function __construct(?int $id, ?User $user) {
        $this->id = $id;
        $this->user = $user;
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function getUser(): ?User {
        return $this->user;
    }
}