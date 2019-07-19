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

	public function Merchandise()
	{
		return $this->hasOne('ShopProject\Models\DataCollection\Merchandise', 'id', 'merchandise_id');
	}

	public function User()
	{
		return $this->belongsTo('ShopProject\Models\DataCollection\User', 'user_id', 'id');
	}
}
