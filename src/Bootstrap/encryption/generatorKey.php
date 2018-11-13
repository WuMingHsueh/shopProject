<?php
require dirname(dirname(dirname(__DIR__))) . "/vendor/autoload.php";

use Defuse\Crypto\Key;
use Defuse\Crypto\Crypto;

$key = Key::createNewRandomKey();
$encodeKey = $key->saveToAsciiSafeString();

$getKey = Key::loadFromAsciiSafeString($encodeKey);
$password = "IISuess1120";
$encryptPassword = Crypto::encrypt($password, $getKey);
print $encryptPassword;
print Crypto::decrypt($encryptPassword, $key);
// function encrypt($pure_string, $encryption_key) {
//     $iv_size = \mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);
//     $iv = \mcrypt_create_iv($iv_size, MCRYPT_RAND);
//     $encrypted_string = \mcrypt_encrypt(MCRYPT_BLOWFISH, $encryption_key, utf8_encode($pure_string), MCRYPT_MODE_ECB, $iv);
//     return $encrypted_string;
// }

// function decrypt($encrypted_string, $encryption_key) {
//     $iv_size = \mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);
//     $iv = \mcrypt_create_iv($iv_size, MCRYPT_RAND);
//     $decrypted_string = \mcrypt_decrypt(MCRYPT_BLOWFISH, $encryption_key, $encrypted_string, MCRYPT_MODE_ECB, $iv);
//     return $decrypted_string;
// }

// $password = "IISuess1120";
// print encrypt($password, $encodeKey);
