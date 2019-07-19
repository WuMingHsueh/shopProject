<?php

namespace ShopProject\Middleware;

use ShopProject\Service\Middleware\IMiddlewareLayer;
use ShopProject\IEnvironment;
use Pimple\Container;
use Closuer;

class CSRF implements IMiddlewareLayer
{
	private $page;
	private $session;

	public function __construct(Container $container)
	{
		$this->page = $container['page'];
		$this->session = $container['session'];
	}

	public function handle($request, Closuer $next, $response)
	{
		$this->session->open(IEnvironment::SESSION_PATH_NAME['CSRF']['PATH'], IEnvironment::SESSION_PATH_NAME['CSRF']['NAME']);
		$sessionToken = $this->session->read(\session_id());
		return ($sessionToken == $request->token) ? $next($request, $response) : $response->redirect(IEnvironment::ROUTER_START . '/error/CSRF')->send();
	}
}
