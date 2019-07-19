<?php

namespace ShopProject\Controllers\Transaction;

use Pimple\Container;
use ShopProject\IEnvironment;
use ShopProject\Models\DataCollection\Transaction;

class TransactionController
{
	private $session;
	private $page;

	public function __construct(Container $container)
	{
		$this->session = $container['session'];
		$this->page = $container['page'];
	}

	public function transactionListPage($request, $response)
	{
		# code...
	}
}
