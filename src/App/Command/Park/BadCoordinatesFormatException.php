<?php

namespace Fulll\App\Command\Park;

class BadCoordinatesFormatException extends \InvalidArgumentException {
    public function __construct($message, $code = 0, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}