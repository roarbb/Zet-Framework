<?php


namespace Zet;


class App
{
    public function start(Router $router, $configurator)
    {
        $router->match($configurator);
    }
}