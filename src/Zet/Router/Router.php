<?php


namespace Zet\Router;


use Zet\Configurator;

interface Router
{
    public function setEndpoint($requestType, $map, $controller, $controllerMethod = 'index');

    public function match(Configurator $configurator);
}