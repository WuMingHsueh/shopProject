<?php
namespace ShopProject\Service;

use Klein\klein;
use Klein\Request;
use ShopProject\IEnvironment;

class Routers
{
    private $klein;
    private $kleinRequest;

    private $routers = [
        // ["method" => "post", 'path' => "", "controller" => "", "responseMethod" => "", "canActivate" => "" ],
        
    ];

    public function __construct()
    {
        $this->initSubDirectory(); // 若專案目錄是 "sub Directory" 則加入此函數設定$_SERVER['REQUEST_URI']

        $this->klein = new Klein;
        foreach ($this->routers as $router) {
            $this->klein->respond($router['method'], $router['path'], function ($request, $resopnse) use ($router) {
                $controller = (isset($router["injectionService"]))? new $router['controller'](new $router["injectionService"]) : new $router['controller'];
                try {
                    return $controller->{$router['responseMethod']}($request);
                } catch (\Exception $e) {
                    $resopnse->code($e->getCode());
                    return $e->getMessage();
                }
            });
        }
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
}
