<?php

namespace Fulll\App\Query\User;

use Fulll\App\Query\HandlerInterface;
use Fulll\App\Query\QueryInterface;
use Fulll\App\Query\UserNotFoundException;
use Fulll\Domain\Query\QueryRepositoryInterface;
use Fulll\Domain\Query\User;

class Handler implements HandlerInterface {
    private QueryRepositoryInterface $queryRepository;

    public function __construct(QueryRepositoryInterface $queryRepository) {
        $this->queryRepository = $queryRepository;
    }

    public function execute(QueryInterface $query): User {
        $user = $this->queryRepository->findUser($query->getUserId());
        if (empty($user)) {
            throw new UserNotFoundException("User was not found for the given id");
        }

        return $user;
    }
}