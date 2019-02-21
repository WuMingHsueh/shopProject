<?php
require dirname(dirname(dirname(__DIR__))) . "/vendor/autoload.php";
require dirname(__DIR__) . '/database.php';

use Illuminate\Database\Capsule\Manager as DB;

DB::schema()->dropIfExists('users');
DB::schema()->create('users', function ($table) {
    $table->increments('id');
    $table->string('email', 150)->unique();
    $table->string('password', 296);
    $table->string('nickname', 50);

    // 帳號類型 (type) 用於識別會員身分
    // A (admin): 管理者
    // G (General): 一般會員
    $table->string('type', 1)->default('G');
    $table->timestamps();
    $table->unique(['email'], 'user_email_uk');
});
