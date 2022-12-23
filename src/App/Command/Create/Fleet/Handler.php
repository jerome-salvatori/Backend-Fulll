<?php

namespace Fulll\App\Command\Create\Fleet;

use Fulll\App\Command\HandlerInterface;
use Fulll\App\Command\CommandInterface;
use Fulll\Domain\Create\CreateRepositoryInterface;

class Handler implements HandlerInterface {
    private CreateRepositoryInterface $createRepository;

    public function __construct(CreateRepositoryInterface $createRepository) {
        $this->createRepository = $createRepository;
    }

    public function execute(CommandInterface $command): void {
        $this->createRepository->createFleet($command->getUserId());
    }
}