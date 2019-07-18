<?php

namespace ShopProject\Service\Middleware;

use Closure;

interface IMiddlewareLayer
{
	public function handle($request, Closure $next, $response);
}
