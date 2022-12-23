<?php

namespace Fulll\App\Query;

use Fulll\Domain\Query\QueryObjectInterface;

interface HandlerInterface {
    public function execute(QueryInterface $query): QueryObjectInterface;
}