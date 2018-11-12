<?php
namespace ShopProject\Service;

use Klein\klein;
use Klein\Request;
use ShopProject\IEnvironment;

class RoutersPage
{
    private $klein;
    private $kleinRequest;

    private $routersPage = [
        // ["method" => "get", 'path' => "", "controller" => "", "responseMethod" => "", "viewLayout" => "", "viewRender" => ""],
        ["method" => "get", 'path' => "/user/auth/sign-up", "controller" => "ShopProject\Controllers\User\UserAuthController", "responseMethod" => "signUpPage", "viewLayout" => "src/Views/layouts/default.php", "viewRender" => "src/Views/auth/signUp.php"],
        
    ];

    public function __construct()
    {
        $this->initSubDirectory(); // 若專案目錄是 "sub Directory" 則加入此函數設定$_SERVER['REQUEST_URI']

        $this->klein = new Klein;
        foreach ($this->routersPage as $routerPage) {
            $this->klein->respond($routerPage['method'], $routerPage['path'], function ($request, $resopnse, $service) use ($routerPage) {
                $controller = new $routerPage['controller'];
                $service = $controller->{$routerPage['responseMethod']}($request, $service);
                $service->layout($routerPage['viewLayout']);
                $service->render($routerPage['viewRender']);
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