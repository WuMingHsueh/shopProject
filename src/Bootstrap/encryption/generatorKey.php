<?php
require dirname(dirname(dirname(__DIR__))) . "/vendor/autoload.php";

use Defuse\Crypto\Key;
use Defuse\Crypto\Crypto;
use ShopProject\IEnvironment;

$key = Key::createNewRandomKey();
$encodeKey = $key->saveToAsciiSafeString();
$keyConfig = dirname($_SERVER['DOCUMENT_ROOT']) . "/phpWarehouse/" . IEnvironment::PROJECT_NAME . "/config/key.ini";

// file_put_contents($keyConfig, $encodeKey . PHP_EOL, FILE_APPEND);
file_put_contents($keyConfig, $encodeKey);
