<?php
require __DIR__ . "/vendor/autoload.php";

session_start();
require __DIR__ . "/src/Bootstrap/database.php";
require __DIR__ . "/src/Bootstrap/dependencies.php";
require __DIR__ . "/src/Bootstrap/bootstrapRouters.php";
