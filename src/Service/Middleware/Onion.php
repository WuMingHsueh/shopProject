<?php

namespace ShopProject\Service\Middleware;

use Closure;
use InvalidArgumentException;

class Onion
{
	private $layers;

	public function __construct(array $layers = [])
	{
		$this->layers = $layers;
	}

	public function getLayers()
	{
		return $this->layers;
	}

	public function layer($layers)
	{
		if ($layers instanceof Onion) {
			$layers = $layers->getLayers();
		}

		if ($layers instanceof IMiddlewareLayer) {
			$layers = [$layers];
		}

		if (!is_array($layers)) {
			throw new InvalidArgumentException(get_class($layers) . " is not a valid onion layer.");
		}

		return new static(array_merge($this->layers, $layers));
	}

	public function handle($request, Closure $controllerMethod, $response)
	{
		// 靠近核心的內層須先打包，故須將順序反轉過來
		$layers = array_reverse($this->layers);

		// 使用array_reduce把closer一層層傳入，將中介層一層層打包起來
		$onion = array_reduce($layers, function ($nextLayer, $layer) {
			return $this->createLayer($nextLayer, $layer);
		}, $this->createCoreFunction($controllerMethod));
		return $onion($request, $response);
	}

	private function createCoreFunction(Closure $core)
	{
		return function ($request, $response) use ($core) {
			return $core($request, $response);
		};
	}

	private function createLayer($nextLayer, $layer)
	{
		return function ($request, $response) use ($nextLayer, $layer) {
			return $layer->handle($request, $nextLayer, $response);
		};
	}
}
