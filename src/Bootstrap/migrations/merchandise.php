<?php
require dirname(dirname(dirname(__DIR__))) . "/vendor/autoload.php";
require dirname(__DIR__) . '/database.php';

use Illuminate\Database\Capsule\Manager as DB;

DB::schema()->dropIfExists('merchandise');
DB::schema()->create('merchandise', function($table) {
	$table->increments('id');

	// 用於標記商品狀態，已上架的商品才能被消費者看到
	// C (create): 建立中
	// S (sell):   可販售
	$table->string('status', 1)->default('C');
	$table->string('name', 80)->nullable();
	$table->string('name_en', 80)->nullable();
	$table->text('introduction');
	$table->text('introduction_en');
	$table->string('photo', 50)->nullable();
	$table->integer('price')->default(0);
	$table->integer('remain_count')->default(0);
	$table->timestamps();
	$table->index(['status'], 'merchandise_status_idx');
});
