<?php

namespace Fulll\Migrations;

class FirstMigration extends AbstractMigration {
    protected function getStatements(): array {
        $statements = [];
        $statements[] = "
            CREATE TABLE user (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL
            ) ENGINE=INNODB
        ";
        $statements[] = "
            CREATE TABLE vehicle (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                plate_number VARCHAR(255) NOT NULL,
                location_latitude INT NULL,
                location_longitude INT NULL,
                location_altitude VARCHAR(5) NULL
            ) ENGINE=INNODB
        ";
        $statements[] = "CREATE UNIQUE INDEX v_index ON vehicle(plate_number)";
        $statements[] = "
            CREATE TABLE fleet (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL,
                INDEX u_id(user_id),
                FOREIGN KEY(user_id)
                    REFERENCES user(id)
                    ON DELETE CASCADE
            ) ENGINE=INNODB
        ";
        $statements[] = "
            CREATE TABLE fleet_vehicles (
                fleet_id INT NOT NULL,
                vehicle_id INT NOT NULL,
                INDEX f_id(fleet_id),
                INDEX v_id(vehicle_id),
                FOREIGN KEY(fleet_id)
                    REFERENCES fleet(id)
                    ON DELETE CASCADE,
                FOREIGN KEY (vehicle_id)
                    REFERENCES vehicle(id)
                    ON DELETE CASCADE
            ) ENGINE=INNODB
        ";
        $statements[] = "
            CREATE UNIQUE INDEX
            vfu_index
            ON fleet_vehicles(fleet_id, vehicle_id) 
        ";

        return $statements;
    }
}