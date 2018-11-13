<?php
require __DIR__ . "/vendor/autoload.php";

// require __DIR__ . "/src/Bootstrap/bootstrapRouters.php";
require __DIR__ . "/src/Bootstrap/database.php";

use Illuminate\Database\Capsule\Manager as DB;

$ans = DB::select('select version()');
print $ans[0]->version;
