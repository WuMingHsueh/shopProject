<?php
namespace ShopProject;

interface IEnvironment
{
	const PROJECT_NAME = "shopProject";
	const ROUTER_START = "/shopProject/index.php";
	const NAMESPACE_ROOT = "ShopProject";
	const DOCUMENT_ROOT = "/usr/local/var/www";
	// const CONNECTION_NAME = ["default", "sqlserver_dverental"];

	const SESSION_PATH_NAME = [
		'LOGGIN' => ['PATH' => 'login', 'NAME' => 'data'],
		'CSRF'   => ['PATH' => 'form',  'NAME' => 'token']
	];
}
