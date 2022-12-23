<?php

namespace Fulll\Migrations;

use Fulll\Infra\Utils\Manager;

abstract class AbstractMigration {
    protected $manager;

    public function __construct() {
        $this->manager = new Manager;
    }

    public function migrate() {
        foreach($this->getStatements() as $statement) {
            $this->manager->getDb()->exec($statement);
        }
    }

    abstract protected function getStatements(): array;
}