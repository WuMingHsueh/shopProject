<?php

namespace ShopProject\Service\Session;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Stash\Pool;
use Stash\Session;

class SessionService implements ServiceProviderInterface
{
	public function register(Container $pimple)
	{
		$pool = new Pool(StorageDrivers::fileSystem());
		$pimple['session'] = function ($c) use ($pool) {
			return new Session($pool);
		};
	}
}
