<?php

namespace Fulll\Infra;

use Fulll\Domain\Park\ParkRepositoryInterface;
use Fulll\Domain\Park\Vehicle;
use Fulll\Infra\Utils\Manager;
use Fulll\Infra\Utils\Hydrator;

class ParkRepository implements ParkRepositoryInterface {
    const ENTITIES_NAMESPACE = "Fulll\Domain\Park";
    private Manager $manager;
    private Hydrator $hydrator;
    
    public function __construct() {
        $this->manager = new Manager;
        $this->hydrator = new Hydrator;
    }

    public function findVehicle(string $plateNumber): ?Vehicle {
        $sql = "SELECT id, plate_number, location_latitude, location_longitude, location_altitude
            FROM vehicle WHERE plate_number = :plate_number";
        $stmt = $this->manager->getDb()->prepare($sql);
        $stmt->bindValue('plate_number', $plateNumber, \PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        $location = [];
        foreach (["location_latitude", "location_longitude", "location_altitude"] as $locationProp) {
            $locShortProp = explode("_", $locationProp)[1];
            $location[$locShortProp] = $result[$locationProp];
            unset($result[$locationProp]);
        }
        $location["__meta_data__"] = self::ENTITIES_NAMESPACE."\Location";
        if (!empty($location["latitude"])) {
            $result["location"] = $location;
        } else {
            $result["location"] = null;
        }
        $result["__meta_data__"] = self::ENTITIES_NAMESPACE."\Vehicle";

        return $this->hydrator->hydrate($result);
    }

    public function saveVehicle(Vehicle $vehicle): void {
        $sql = "UPDATE vehicle 
            SET location_latitude = :latitude,
            location_longitude = :longitude,
            location_altitude = :altitude
            WHERE id = :id";
        $stmt = $this->manager->getDb()->prepare($sql);
        $stmt->execute([
            "latitude" => $vehicle->getLocation()->getLatitude(),
            "longitude" => $vehicle->getLocation()->getLongitude(),
            "altitude" => $vehicle->getLocation()->getAltitude(),
            "id" => $vehicle->getId()
        ]);
    }
}