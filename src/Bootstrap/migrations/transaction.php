<?php
require dirname(dirname(dirname(__DIR__))) . "/vendor/autoload.php";
require dirname(__DIR__) . '/database.php';

use Illuminate\Database\Capsule\Manager as DB;

DB::schema()->dropIfExists('transaction');
DB::schema()->create('transaction', function($table) {
	$table->increments('id');

	$table->integer('user_id');
	$table->integer('merchandise_id');
	$table->integer('price');
	$table->integer('buy_count');
	$table->integer('total_price');
	$table->timestamps();

	$table->index(['user_id'], 'user_transaction_idx');
});
