<?php


namespace Zet;


use Zet\Router\Router;

class App
{
    public function start(Router $router, $configurator)
    {
        $router->match($configurator);
    }
}