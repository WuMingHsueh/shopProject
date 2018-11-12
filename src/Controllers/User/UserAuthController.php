<?php 
namespace ShopProject\Controllers\User;

class UserAuthController
{
    public function signUpPage($request, $service)
    {
        $service->title = "註冊";
        return $service;
    }
}
