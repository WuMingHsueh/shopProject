<?php

use Pimple\Container;

$container = new Container();
$container->register(new ShopProject\Service\Session\SessionService);
