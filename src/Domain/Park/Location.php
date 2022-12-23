<?php

namespace Fulll\Domain\Park;

use Fulll\App\Command\Park\BadCoordinatesFormatException;

//value object
class Location {
    private float $latitude;
    private float $longitude;
    private ?string $altitude;

    public function __construct(float $latitude, float $longitude, ?string $altitude = null) {
        if (!$this->checkFormat($latitude, $longitude, $altitude)) {
            throw new BadCoordinatesFormatException("Format for the given coordinates is not correct");
        }
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->altitude = $altitude;
    }

    private function checkFormat(float $latitude, float $longitude, ?string $altitude): bool {
        if ($latitude < -90 || $latitude > 90 || $longitude < -180 || $longitude > 180) {
            return false;
        }
        if (!preg_match('([0-9]|[1-9][0-9]{1,3})m', $altitude)) {
            return false;
        }

        return true;
    }

    public function getLatitude(): float {
        return $this->latitude;
    }

    public function getLongitude(): float {
        return $this->longitude;
    }

    public function getAltitude(): ?string {
        return $this->altitude;
    }
}