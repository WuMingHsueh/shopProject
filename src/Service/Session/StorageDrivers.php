<?php

namespace ShopProject\Service\Session;

use Stash\Driver\FileSystem;
use Stash\Driver\Sqlite;
use Stash\Driver\Apc;
use Stash\Driver\Redis;
use Stash\Driver\Memcache;
use ShopProject\IEnvironment;

class StorageDrivers
{
	public static function fileSystem(): FileSystem
	{
		$path = dirname((isset($_SERVER['DOCUMENT_ROOT']) and $_SERVER['DOCUMENT_ROOT'] != '') ? $_SERVER['DOCUMENT_ROOT'] : IEnvironment::DOCUMENT_ROOT) . "/phpWarehouse/" . IEnvironment::PROJECT_NAME . "/cache/";
		return new FileSystem(['path' => $path]);
	}

	public static function sqlite(): Sqlite
	{
		$path = dirname((isset($_SERVER['DOCUMENT_ROOT']) and $_SERVER['DOCUMENT_ROOT'] != '') ? $_SERVER['DOCUMENT_ROOT'] : IEnvironment::DOCUMENT_ROOT) . "/phpWarehouse/" . IEnvironment::PROJECT_NAME . "/cache/";
		return new Sqlite(['path' => $path]);
	}

	public static function apc(): Apc
	{
		return new Apc(
			[
				'ttl' => (int)ini_get('session.gc_maxlifetime'),
				'namespace' => IEnvironment::NAMESPACE_ROOT
			]
		);
	}

	public static function redis(): Redis
	{
		$path = dirname((isset($_SERVER['DOCUMENT_ROOT']) and $_SERVER['DOCUMENT_ROOT'] != '') ? $_SERVER['DOCUMENT_ROOT'] : IEnvironment::DOCUMENT_ROOT) . "/phpWarehouse/" . IEnvironment::PROJECT_NAME . "/config/cache.yml";
		return new Redis(\yaml_parse_file($path));
	}

	public static function memcache(): Memcache
	{
		$path = dirname((isset($_SERVER['DOCUMENT_ROOT']) and $_SERVER['DOCUMENT_ROOT'] != '') ? $_SERVER['DOCUMENT_ROOT'] : IEnvironment::DOCUMENT_ROOT) . "/phpWarehouse/" . IEnvironment::PROJECT_NAME . "/config/cache.yml";
		return new Memcache(\yaml_parse_file($path));
	}
}
