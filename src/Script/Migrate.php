<?php

namespace Fulll\Script;

include "/var/www/html/Backend/PHP/Boilerplate/vendor/autoload.php";

use Fulll\Migrations\FirstMigration;

$migration = new FirstMigration;

$migration->migrate();