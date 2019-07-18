<?php

namespace ShopProject\Middleware;

use ShopProject\Service\Middleware\IMiddlewareLayer;
use ShopProject\Models\DataCollection\User;
use ShopProject\IEnvironment;
use Pimple\Container;
use Closure;

class AuthUserAdmin implements IMiddlewareLayer
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
		$userId = $this->session->read(\session_id());
		if (is_null($userId) or $userId == '') {
			return $response->redirect(IEnvironment::ROUTER_START . '/user/auth/status')->send();
		}
		$user = User::where('email', $userId['email'])->get()[0];
		if ($user->type == 'A') {
			return $next($request, $response);
		} else {
			return $response->redirect(IEnvironment::ROUTER_START . '/user/auth/status')->send();
		}
	}
}
