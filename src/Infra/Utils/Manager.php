<?php

namespace Fulll\Infra\Utils;

class Manager {
    private $db;

    public function __construct() {
        $this->db = new \PDO('mysql:host=127.0.0.1;dbname=fulll_backend', 'admin', 'admin', [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]);
    }

    public function getDb() {
        return $this->db;
    }
}