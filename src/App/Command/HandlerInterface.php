<?php

namespace Fulll\App\Command;

interface HandlerInterface {
    public function execute(CommandInterface $command): void;
}