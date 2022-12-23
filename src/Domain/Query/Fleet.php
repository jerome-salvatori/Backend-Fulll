<?php

namespace Fulll\Domain\Query;

class Fleet implements QueryObjectInterface {
    private int $id;
    private User $user;
    private iterable $vehicles;

    public function getId(): int {
        return $this->id;
    }

    public function getUser(): User {
        return $this->user;
    }

    public function getVehicles(): iterable {
        return $this->vehicles;
    }
}