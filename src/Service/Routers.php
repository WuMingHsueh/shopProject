<?php

namespace ShopProject\Service;

use Klein\klein;
use Klein\Request;
use Pimple\Container;
use ShopProject\IEnvironment;
use ShopProject\Service\Middleware\Onion;

class Routers
{
	private $klein;
	private $kleinRequest;
	private $container;

	private $routers = [
		// ["method" => "post", 'path' => "", "controller" => "", "responseMethod" => "", "middlewareLayers" => [] ],

	];

	private $routersPage = [
		// ["method" => "get", 'path' => "", "controller" => "", "responseMethod" => "", "viewLayout" => "", "viewRender" => "", "middlewareLayers" => [] ],
		["method" => "get",  'path' => "/user/auth/sign-up",  "controller" => "ShopProject\Controllers\User\UserAuthController", "responseMethod" => "signUpPage", "middlewareLayers" => []],
		["method" => "get",  'path' => "/user/auth/status",  "controller" => "ShopProject\Controllers\User\UserAuthController", "responseMethod" => "signStatus", "middlewareLayers" => []],
		["method" => "post", 'path' => "/user/auth/sign-up",  "controller" => "ShopProject\Controllers\User\UserAuthController", "responseMethod" => "signUpProcess", "middlewareLayers" => []],
		["method" => "get",  'path' => "/user/auth/sign-in",  "controller" => "ShopProject\Controllers\User\UserAuthController", "responseMethod" => "signInPage", "middlewareLayers" => []],
		["method" => "post", 'path' => "/user/auth/sign-in",  "controller" => "ShopProject\Controllers\User\UserAuthController", "responseMethod" => "signInProcess", "middlewareLayers" => []],
		["method" => "get",  'path' => "/user/auth/sign-out", "controller" => "ShopProject\Controllers\User\UserAuthController", "responseMethod" => "signOutProcess", "middlewareLayers" => []],
		["method" => "get",  'path' => "/merchandise",        "controller" => "ShopProject\Controllers\Merchandise\MerchandiseController", "responseMethod" => "merchandiseListPage", "middlewareLayers" => []],
		["method" => "get",  'path' => "/merchandise/create", "controller" => "ShopProject\Controllers\Merchandise\MerchandiseController", "responseMethod" => "merchandiseCreateProcess", "middlewareLayers" => ["ShopProject\Middleware\AuthUserAdmin"]],
		["method" => "get",  'path' => "/merchandise/manage", "controller" => "ShopProject\Controllers\Merchandise\MerchandiseController", "responseMethod" => "merchandiseManageListPage", "middlewareLayers" => ["ShopProject\Middleware\AuthUserAdmin"]],
		["method" => "get",  'path' => "/merchandise/[:merchandiseId]", "controller" => "ShopProject\Controllers\Merchandise\MerchandiseController", "responseMethod" => "merchandiseItemPage", "middlewareLayers" => []],
		["method" => "get",  'path' => "/merchandise/[:merchandiseId]/edit", "controller" => "ShopProject\Controllers\Merchandise\MerchandiseController", "responseMethod" => "merchandiseItemEditPage", "middlewareLayers" => ["ShopProject\Middleware\AuthUserAdmin"]],
		["method" => "put",  'path' => "/merchandise/[:merchandiseId]", "controller" => "ShopProject\Controllers\Merchandise\MerchandiseController", "responseMethod" => "merchandiseItemUpdateProcess", "middlewareLayers" => ["ShopProject\Middleware\AuthUserAdmin"]],
		["method" => "post", 'path' => "/merchandise/[:merchandiseId]/buy", "controller" => "ShopProject\Controllers\Merchandise\MerchandiseController", "responseMethod" => "merchandiseItemBuyProcess", "middlewareLayers" => ["ShopProject\Middleware\AuthUser"]],
	];

	public function __construct(Container $container = null)
	{
		$this->container = $container ?? new Container();
		$this->initSubDirectory(); // 若專案目錄是 "sub Directory" 則加入此函數設定$_SERVER['REQUEST_URI']

		$this->klein = new Klein;
		$this->respondAPI();
		$this->respondPage();
		$this->klein->dispatch($this->kleinRequest);

		// initSubDirectory function (2) content
		// $this->klein->dispatch();
	}

	private function initSubDirectory()
	{
		$this->kleinRequest = Request::createFromGlobals();
		$uri = $this->kleinRequest->server()->get('REQUEST_URI');
		$this->kleinRequest->server()->set('REQUEST_URI', substr($uri, strlen(IEnvironment::ROUTER_START)));

		// https://github.com/klein/klein.php/wiki/Sub-Directory-Installation
		//
		// (2)
		// This might also work,it doesn't need a custom request object
		// $_SERVER['REQUEST_URI'] = substr($_SERVER['REQUEST_URI'], strlen(IEnvironment::ROUTER_START));
	}

	public function respondAPI()
	{
		foreach ($this->routers as $router) {
			$this->klein->respond($router['method'], $router['path'], function ($request, $response) use ($router) {
				$controller = new $router['controller']($this->container);
				try {
					if ((empty($router['middlewareLayers']))) {
						return call_user_func([$controller, $router['responseMethod']], $request, $response);
					} else {
						return $this->provideMiddleware(
							$router['middlewareLayers'],
							$this->container,
							$request,
							$response,
							$controller,
							$router['responseMethod']
						);
					}
				} catch (\Exception $e) {
					$response->code($e->getCode());
					return $e->getMessage();
				}
			});
		}
	}

	public function respondPage()
	{
		foreach ($this->routersPage as $routerPage) {
			$this->klein->respond($routerPage['method'], $routerPage['path'], function ($request, $response, $service) use ($routerPage) {
				unset($this->container['page']);
				$this->container['page'] = function ($c) use ($service) {
					return  $service;
				};
				$controller = new $routerPage['controller']($this->container);
				if ((empty($routerPage['middlewareLayers']))) {
					call_user_func([$controller, $routerPage['responseMethod']], $request, $response);
				} else {
					$this->provideMiddleware(
						$routerPage['middlewareLayers'],
						$this->container,
						$request,
						$response,
						$controller,
						$routerPage['responseMethod']
					);
				}
				$service = $this->container['page'];
			});
		}
	}

	private function provideMiddleware(array $middlewares, $container, $request, $response, $controller, $method)
	{
		// 創建 onion 並在各層中注入相依物件
		$onion = new Onion(\array_map(function ($class) use ($container) {
			return new $class($container);
		}, $middlewares));

		// 依序執行個中介層邏輯
		return $onion->handle($request, function ($request, $response) use ($controller, $method) {
			return $controller->{$method}($request, $response);
		}, $response);
	}
}
