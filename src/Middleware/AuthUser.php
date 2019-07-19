<?php

namespace ShopProject\Middleware;

use ShopProject\Service\Middleware\IMiddlewareLayer;
use ShopProject\IEnvironment;
use Pimple\Container;
use Closure;

class AuthUser implements IMiddlewareLayer
{
	private $page;
	private $session;

	public function __construct(Container $container)
	{
		$this->page = $container['page'];
		$this->session = $container['session'];
	}

	public function handle($request, Closure $next, $response)
	{
		$this->session->open(IEnvironment::SESSION_PATH_NAME['LOGGIN']['PATH'], IEnvironment::SESSION_PATH_NAME['LOGGIN']['NAME']);
		$userSession = $this->session->read(\session_id());
		return (!is_null($userSession) and $userSession <> '') ? $next($request, $response) : $response->redirect(IEnvironment::ROUTER_START . '/user/auth/sign-in')->send();
	}
}
