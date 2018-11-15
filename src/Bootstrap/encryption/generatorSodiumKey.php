<?php
require dirname(dirname(dirname(__DIR__))) . "/vendor/autoload.php";

use ParagonIE\Halite\KeyFactory;
use ShopProject\IEnvironment;

$keyConfig = dirname($_SERVER['DOCUMENT_ROOT']) . "/phpWarehouse/" . IEnvironment::PROJECT_NAME . "/config/key.ini";
KeyFactory::save(KeyFactory::generateAuthenticationKey(), $keyConfig);
