<?php

namespace Features\bootstrap\Utils;

class IdGenerator {
    const ENTITY_IDS = ["fleetMaxId", "vehicleMaxId", "userMaxId"];

    private int $fleetMaxId;
    private int $vehicleMaxId;
    private int $userMaxId;

    public function __construct() {
        foreach (self::ENTITY_IDS as $entityId) {
            $entityIdValue = apcu_fetch($entityId);
            if (empty($entityIdValue)) {
                $entityIdValue = 0;
            }

            $this->$entityId = $entityIdValue;
        }
    }

    public function getEntityNextId(string $entityIdName): int {
        if (!in_array($entityIdName, self::ENTITY_IDS)) {
            throw new \InvalidArgumentException("Given string is not a valid entity id name");
        }

        $id = $this->$entityIdName + 1;
        $this->$entityIdName++;
        apcu_store($entityIdName, $this->$entityIdName);

        return $id;
    }
}