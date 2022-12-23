<?php

namespace Fulll\Infra;

use Fulll\Domain\Register\RegisterRepositoryInterface;
use Fulll\Infra\Utils\Manager;
use Fulll\Infra\Utils\Hydrator;
use Fulll\Domain\Register\Fleet;
use Fulll\Domain\Register\Vehicle;

class RegisterRepository implements RegisterRepositoryInterface {
    const ENTITIES_NAMESPACE = "Fulll\Domain\Register";
    private Manager $manager;
    private Hydrator $hydrator;
    
    public function __construct() {
        $this->manager = new Manager;
        $this->hydrator = new Hydrator;
    }

    public function findFleet(int $fleetId): ?Fleet {
        $sql = "SELECT id FROM fleet WHERE id = :fleet_id";
        $stmt = $this->manager->getDb()->prepare($sql);
        $stmt->bindValue('fleet_id', $fleetId, \PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        if (empty($result)) {
            return null;
        }
        $sqlVehicles = "SELECT vehicle.id, vehicle.plate_number FROM vehicle
            JOIN fleet_vehicles ON fleet_vehicles.vehicle_id = vehicle.id
            WHERE fleet_vehicles.fleet_id = :fleet_id";
        $stmt = $this->manager->getDb()->prepare($sqlVehicles);
        $stmt->bindValue('fleet_id', $fleetId, \PDO::PARAM_INT);
        $resultVehicles = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $result["vehicles"] = $resultVehicles;
        $result["__meta_data__"] = self::ENTITIES_NAMESPACE."\Fleet";
        return $this->hydrator->hydrate($result);
    }

    public function findVehicle(string $plateNumber): ?Vehicle {
        $sql = "SELECT id, plate_number FROM vehicle WHERE plate_number = :plate_number";
        $stmt = $this->manager->getDb()->prepare($sql);
        $stmt->bindValue('plate_number', $plateNumber, \PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        if (empty($result)) {
            return null;
        }
        $result["__meta_data__"] = self::ENTITIES_NAMESPACE."\Vehicle";
        return $this->hydrator->hydrate($result);
    }
    
    public function saveFleet(Fleet $fleet): void {
        $sql = "INSERT IGNORE INTO fleet_vehicles (fleet_id, vehicle_id) VALUES ";
        $vehicles = $fleet->getVehicles();
        foreach ($fleet->getVehicles() as $vehicle) {
            $sql .= "(".$fleet->getId().", ".$vehicle->getId()."), ";
        }
        $sql = substr($sql, 0, -2);
        $this->manager->getDb()->exec($sql);
    }
}