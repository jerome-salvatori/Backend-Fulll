<?php

namespace Fulll\App\Command\Register;

class VehicleAlreadyInFleetException extends \BadMethodCallException {
    public function __construct($message, $code = 0, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}