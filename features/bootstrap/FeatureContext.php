<?php

//namespace Features\bootstrap;

use Behat\Behat\Context\Context;
use Fulll\Domain\Register\Vehicle;
use Fulll\Domain\Register\Fleet;
use Fulll\Domain\Park\Vehicle as ParkVehicle;
use Fulll\Domain\Park\Fleet as ParkFleet;
use Fulll\App\Command\Register;
use Fulll\App\Command\Park;
use Fulll\App\Command\Create;
use Fulll\App\Query;
use Features\bootstrap\TestRepositories\CreateRepository;
use Features\bootstrap\TestRepositories\ParkRepository;
use Features\bootstrap\TestRepositories\RegisterRepository;
use Features\bootstrap\TestRepositories\QueryRepository;
use Fulll\App\Command\Register\VehicleAlreadyInFleetException;
use Fulll\App\Command\Park\AlreadyParkedInLocationException;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context
{
    private CreateRepository $createRepository;
    private ParkRepository $parkRepository;
    private RegisterRepository $registerRepository;
    private QueryRepository $queryRepository;
    private Register\Handler $registerHandler;
    private Park\Handler $parkHandler;
    private Create\User\Handler $createUserHandler;
    private Create\Vehicle\Handler $createVehicleHandler;
    private Create\Fleet\Handler $createFleetHandler;
    private Query\Fleet\Handler $queryFleetHandler;
    private Query\Vehicle\Handler $queryVehicleHandler;
    private $userCreate;

    public function __construct() {
        $this->createRepository = new CreateRepository;
        $this->parkRepository = new ParkRepository;
        $this->registerRepository = new RegisterRepository;
        $this->queryRepository = new QueryRepository;
        $this->registerHandler = new Register\Handler($this->registerRepository);
        $this->parkHandler = new Park\Handler($this->parkRepository);
        $this->createUserHandler = new Create\User\Handler($this->createRepository);
        $this->createVehicleHandler = new Create\Vehicle\Handler($this->createRepository);
        $this->createFleetHandler = new Create\Fleet\Handler($this->createRepository);
        $this->queryFleetHandler = new Query\Fleet\Handler($this->queryRepository);
        $this->queryVehicleHandler = new Query\Vehicle\Handler($this->queryRepository);
    }

    /**
     * @Given my fleet
     */
    public function givenMyFleet() {
        $this->createUserHandler->execute(
            new Create\User\Command(
                md5(rand())
            )
        );

        $userCreate = apcu_fetch("user_create");
        $userId = $userCreate[count($userCreate) - 1]["id"];
        $this->createFleetHandler->execute(
            new Create\Fleet\Command(
                $userId
            )
        );
    }
     
    /**
     * @And a vehicle
     */
    public function andAVehicle() {
        $this->plateNumber = md5(rand());
        $this->createVehicleHandler->execute(
            new Create\Vehicle\Command(
                md5(rand()),
                $this->plateNumber
            )
        );
    }

     /** 
     * @When I register this vehicle into my fleet
     */
    public function registerVehicle() {
        $fleetRegister = apcu_fetch("fleet_register");
        $this->registerFleetId = $fleetRegister[count($fleetRegister) - 1]["id"];
        $this->registerVehiclePlate = $this->plateNumber;

        $this->registerHandler->execute(
            new Register\Command(
                $this->registerFleetId,
                $this->plateNumber
            )
        );
    }

    /**
     * @Then this vehicle should be part of my vehicle fleet
     */
    public function checkVehiclePartOfFleet() {
        $queryVehicle = $this->queryVehicleHandler->execute(
            new Query\Vehicle\Query(
                null,
                $this->registerVehiclePlate
            )
        );
        $queryFleet = $this->queryFleetHandler->execute(
            new Query\Fleet\Query(
                $this->registerFleetId
            )
        );
        if (!in_array($queryVehicle, $queryFleet->getVehicles())) {
            throw new \RunTimeException("Vehicle has not been properly registered into fleet");
        }
    }

    /**
     * Given my fleet
     * @And a vehicle
     * @And I have registered this vehicle into my fleet
     * @When I try to register this vehicle into my fleet
     */
    public function tryRegisterTwice() {
        $this->registerTwiceException = null;
        try {
            $this->registerHandler->execute(
                new Register\Command(
                    $this->registerFleetId,
                    $this->registerVehiclePlate
                )
            );
        } catch (VehicleAlreadyInFleetException $e) {
            $this->registerTwiceException = $e;
        }
    }

    /**
     * @Then I should be informed this this vehicle has already been registered into my fleet
     */
    public function checkRegisterException() {
        if (empty($this->registerTwiceException)) {
            throw new \RunTimeException("Test failed, a vehicle should not be registered twice in the same fleet");
        }
    }

    /**
     * Given my fleet
     * @And the fleet of another user
     * @And a vehicle
     * @And this vehicle has been registered into the other user's fleet
     */
    public function createRegisterOther() {
        $this->createUserHandler->execute(
            new Create\User\Command(
                md5(rand())
            )
        );
        $plateNumber = md5(rand());
        $this->createVehicleHandler->execute(
            new Create\Vehicle\Command(
                md5(rand()),
                $plateNumber
            )
        );

        $userCreate = apcu_fetch("user_create");
        $userId = $userCreate[count($userCreate) - 1]["id"];
        $this->createFleetHandler->execute(
            new Create\Fleet\Command(
                $userId
            )
        );

        $this->otherVehiclePlate = $plateNumber;
        $fleetRegister = apcu_fetch("fleet_register");
        $otherFleetId = $fleetRegister[count($fleetRegister) - 1]["id"];

        $this->registerHandler->execute(
            new Register\Command(
                $otherFleetId,
                $plateNumber
            )
        );
    }

    /**
     * @When I register this other vehicle into my fleet
     */
    public function registerOtherInMyFleet() {
        $this->registerHandler(
            new Register\Command(
                $this->registerFleetId,
                $this->otherVehiclePlate
            )
        );
    }       

    /**
     * @Then this other vehicle should be part of my vehicle fleet
     */
    public function checkOtherVehicleInMyFleet() {
        $queryFleet = $this->queryFleetHandler->execute(
            new Query\Fleet\Query(
                $this->registerFleetId
            )
        );
        $queryOtherVehicle = $this->queryVehicleHandler->execute(
            new Query\Vehicle\Query(
                $this->otherVehiclePlate
            )
        );
        if (!in_array($queryOtherVehicle, $queryFleet->getVehicles())) {
            throw new \RunTimeException("Vehicle should be able to be registered in two separate fleets");
        }
    }

    /**
     * And a location
     * @When I park my vehicle at this location
     * Then the known location of my vehicle should verify this location
     */
    public function parkVehicle() {
        $this->location = ["latitude" => 45, "longitude" => 160, "altitude" => "120m"];
        $this->parkHandler->execute(
            new Park\Command(
                $this->registerVehiclePlate,
                $this->location
            )
        );
    }

    /** 
     * And a location
     * And my vehicle has been parked into this location
     * @When I try to park my vehicle at this location
     */
    public function parkVehicleTwice() {
        $this->alreadyParkedException = null;
        try {
            $this->parkHandler->execute(
                new Park\Command(
                    $this->registerVehiclePlate,
                    $this->location
                )
            );
        } catch (AlreadyParkedInLocationException $e) {
            $this->alreadyParkedException = $e;
        }
    }

    /**
     * @Then I should be informed that my vehicle is already parked at this location
     */
    public function checkExceptionParkedTwice() {
        if (empty($this->alreadyParkedException)) {
            throw new \RunTimeException("Parking a vehicle twice in the same location should have thrown an exception");
        }
    }
}
