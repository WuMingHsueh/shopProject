<?php

namespace ShopProject\Controllers\Merchandise;

use Pimple\Container;

class MerchandiseController
{
	private $session;

	public function __construct(Container $container)
	{
		$this->session = $container['session'];
	}

	public function merchandiseCreateProcess($request, $service, $response)
	{
		# code...
	}
}
