<?php
require dirname(dirname(dirname(__DIR__))) . "/vendor/autoload.php";

use ParagonIE\Halite\KeyFactory;
use ShopProject\IEnvironment;

$keyConfig = dirname(empty($_SERVER['DOCUMENT_ROOT']) ? (IEnvironment::DOCUMENT_ROOT) : $_SERVER['DOCUMENT_ROOT']) . "/phpWarehouse/" . IEnvironment::PROJECT_NAME . "/config/key.ini";
KeyFactory::save(KeyFactory::generateAuthenticationKey(), $keyConfig);
