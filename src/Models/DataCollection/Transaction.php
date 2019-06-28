<?php
namespace ShopProject\Models\DataCollection;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
	protected $table = 'transaction';
	protected $primaryKey = 'id';
	protected $fillable = [
		'user_id',
		'merchandise_id',
		'price',
		'buy_count',
		'total_price',
	];
}
