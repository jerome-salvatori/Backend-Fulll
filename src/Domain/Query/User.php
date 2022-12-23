<?php

namespace Fulll\Domain\Query;

class User implements QueryObjectInterface {
    private int $id;
    private string $name;

    public function getId(): int {
        return $this->id;
    }

    public function getName(): string {
        return $this->name;
    }
}