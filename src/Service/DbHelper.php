<?php
namespace ShopProject\Service;

use ShopProject\IEnvironment;

use Illuminate\Database\Capsule\Manager as DB;

class DbHelper
{
    private $db;

    public function __construct()
    {
        $params = $this->dbConfigFile();

        $this->db = new DB;
        $this->addConnectionDB($params);
        $this->db->setAsGlobal();
        $this->db->bootEloquent();
    }

    private function addConnectionDB(array $params, string $connectionName = "default")
    {
        $this->db->addConnection(
            [
                'driver'   => $params['driver'],
                'host'     => $params['host'],
                'database' => $params['database'],
                'username' => $params['username'],
                'password' => $params['password'],
                'port'     => $params['port'],
                'charset'  => $params['charset'],
            ],
            $connectionName
        );
    }

    private function dbConfigFile($fileName = "database.ini") : array
    {
        $configPath = dirname($_SERVER['DOCUMENT_ROOT']) . "/phpWarehouse/" . IEnvironment::PROJECT_NAME . "/config/$fileName";
        $params = parse_ini_file($configPath);
        return $params;
    }
}
