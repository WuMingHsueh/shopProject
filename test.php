<?php
require __DIR__ . "/vendor/autoload.php";

require __DIR__ . "/src/Bootstrap/database.php";

use ShopProject\Models\DataCollection\User;
use ShopProject\IEnvironment;
// use ParagonIE\Halite\KeyFactory;
// use ParagonIE\Halite\HiddenString;
// use ParagonIE\Halite\Password;

// $keyConfig = dirname($_SERVER['DOCUMENT_ROOT']) . "/phpWarehouse/" . IEnvironment::PROJECT_NAME . "/config/key.ini";
// $key = KeyFactory::loadEncryptionKey($keyConfig);

// $dbResponse = User::where('email', "")->select('email', 'password')->first();
// Password::verify(new HiddenString(""), $dbResponse->password, $key);

// if (!empty($dbResponse) and
//     !Password::verify(new HiddenString(""), $dbResponse->password, $key)
// ) {
//     print "密碼錯誤";
// }
// print IEnvironment::DOCUMENT_ROOT;
// print PHP_EOL;
// print dirname($_SERVER['DOCUMENT_ROOT'] ?? IEnvironment::DOCUMENT_ROOT);
// print PHP_EOL;
// print empty($_SERVER['DOCUMENT_ROOT']) ? (IEnvironment::DOCUMENT_ROOT) : $_SERVER['DOCUMENT_ROOT'];
// print PHP_EOL;
// print dirname($_SERVER['DOCUMENT_ROOT'] ?? IEnvironment::DOCUMENT_ROOT) . "/phpWarehouse/" . IEnvironment::PROJECT_NAME . "/config/key.ini";
print uniqid();
print PHP_EOL;
print bin2hex(random_bytes(8));
print PHP_EOL;
?>
<!-- <hr>
<form action="<?= IEnvironment::ROUTER_START ?>/merchandise/test" method="post">
	<input type="hidden" name="_method" value="put" />
	<input type="text" name="number">
	<button type="submit">ok</button>
</form> -->
