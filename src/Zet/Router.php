<?php


namespace Zet;


use Zet\Exceptions\RouterException;

class Router
{
    private $endpoints = array();

    public function match($configurator)
    {
        $request = new Request;
        $endpoints = $this->getEndpoints();
        $requestedRoute = $request->getRequestedRoute();
        $requestType = $request->getType();

        foreach ($endpoints as $endpoint) {
            if($endpoint['requestType'] !== $requestType) {
                continue;
            }

            $controllerNamespace = $configurator->getControllerNamespace();

            if($endpoint['map'] == $requestedRoute) {
                require_once(
                    $configurator->getControllerDir()
                    . $endpoint['controller']
                    . '.php'
                );

                $class = $controllerNamespace . $endpoint['controller'];

                if(method_exists($class, $endpoint['controllerMethod'])) {
                    call_user_func(array($class, $endpoint['controllerMethod']));
                    exit;
                } else {
                    throw new RouterException(
                        'Method ' . $endpoint['controllerMethod'] . ' doesn\'t exists in ' . $endpoint['controller']
                    );
                }
            }
        }

        $errorController = 'ErrorController';
        require_once(
            $configurator->getControllerDir()
            . $errorController
            . '.php'
        );

        header("HTTP/1.0 404 Not Found - Archive Empty");
        $class = $controllerNamespace . $errorController;
        call_user_func(array($class, 'notFound'));
        exit;
    }

    public function setEndpoint($requestType, $map, $controller, $controllerMethod = 'index')
    {
        $endpoint = array();

        $endpoint['requestType'] = $requestType;
        $endpoint['map'] = $map;
        $endpoint['controller'] = $controller;
        $endpoint['controllerMethod'] = $controllerMethod;

        $this->addEndpoint($endpoint);
    }

    /**
     * @return mixed
     */
    private function getEndpoints()
    {
        return $this->endpoints;
    }

    /**
     * @param array $endpoint
     */
    private function addEndpoint(array $endpoint)
    {
        $this->endpoints[] = $endpoint;
    }
}