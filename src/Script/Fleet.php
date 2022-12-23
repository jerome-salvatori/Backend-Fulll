<?php

namespace Fulll\Script;

include "/var/www/html/Backend/PHP/Boilerplate/vendor/autoload.php";

use Fulll\App\Command\Create;
use Fulll\App\Command\Register;
use Fulll\App\Command\Park;
use Fulll\Infra\CreateRepository;
use Fulll\Infra\RegisterRepository;
use Fulll\Infra\ParkRepository;

switch($argv[1]) {
    case "create":
        $handler = new Create\Fleet\Handler(new CreateRepository);
        $handler->execute(
            new Create\Fleet\Command($argv[2])
        );
        break;
    case "register-vehicle":
        $handler = new Register\Handler(new RegisterRepository);
        $handler->execute(
            new Register\Command($argv[2], $argv[3])
        );
        break;
    case "localize-vehicle":
        $handler = new Park\Handler(new ParkRepository);
        if (empty($argv[6])) {
            $altitude = null;
        } else {
            $altitude = $argv[6];
        }
        $handler->execute(
            new Park\Command($argv[3], ["latitude" => $argv[4], "longitude" => $argv[5], "altitude" => $altitude])
        );
}