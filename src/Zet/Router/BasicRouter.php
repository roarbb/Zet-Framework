<?php


namespace Zet\Router;


use Zet\Configurator;
use Zet\Exceptions\RouterException;
use Zet\Request;

class BasicRouter implements Router
{
    private $endpoints = array();
    const ENDPOINT_REQUEST_TYPE_LABEL = 'requestType';
    const ENDPOINT_MAP_LABEL = 'map';
    const ENDPOINT_CONTROLLER_LABEL = 'controller';
    const ENDPOINT_CONTROLLER_METHOD_LABEL = 'controllerMethod';

    public function match(Configurator $configurator)
    {
        $request = new Request;
        $endpoints = $this->getEndpoints();
        $requestedRoute = $request->getRequestedRoute();
        $requestType = $request->getType();
        $controllerNamespace = $configurator->getControllerNamespace();

        foreach ($endpoints as $endpoint) {
            $controllerName = $endpoint[self::ENDPOINT_CONTROLLER_LABEL];
            $fullControllerName = $controllerNamespace . $controllerName;
            $controllerMethod = $endpoint[self::ENDPOINT_CONTROLLER_METHOD_LABEL];

            if ($endpoint[self::ENDPOINT_REQUEST_TYPE_LABEL] !== $requestType) {
                continue;
            }

            if ($this->routeMatched($endpoint[self::ENDPOINT_MAP_LABEL], $requestedRoute, $request)) {
                $this->checkCalledControllerMethod($fullControllerName, $controllerMethod);

                $controller = new $fullControllerName($configurator);
                $this->injectDependecies($controller, $fullControllerName, $request);

                $controller->$controllerMethod();
                exit;
            }
        }

        $this->invoke404($configurator);
        exit;
    }

    public function setEndpoint($requestType, $map, $controller, $controllerMethod = 'index')
    {
        $endpoint = array();

        $endpoint[self::ENDPOINT_REQUEST_TYPE_LABEL] = $requestType;
        $endpoint[self::ENDPOINT_MAP_LABEL] = $map;
        $endpoint[self::ENDPOINT_CONTROLLER_LABEL] = $controller;
        $endpoint[self::ENDPOINT_CONTROLLER_METHOD_LABEL] = $controllerMethod;

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

    private function invoke404(Configurator $configurator)
    {
        $controllerNamespace = $configurator->getControllerNamespace();
        $errorController = $configurator->getErrorController();

        header("HTTP/1.0 404 Not Found - Archive Empty");
        call_user_func(array($controllerNamespace . $errorController, 'notFound'));
    }

    private function injectDependecies($controller, $fullControllerName, Request $request)
    {
        if (!method_exists($fullControllerName, 'inject')) {
            return;
        }

        $injectMethod = new \ReflectionMethod($fullControllerName, 'inject');
        $injectParams = $injectMethod->getParameters();

        $classesToInject = array();
        foreach ($injectParams AS $param) {
            $name = '\\' . $param->getClass()->name;

            if($param->getClass()->name === $request->name()) {
                $classesToInject[] = $request;
                continue;
            }

            $classesToInject[] = new $name();
        }

        $injectMethod->invokeArgs($controller, $classesToInject);
    }

    private function routeMatched($map, $requestedRoute, Request $request)
    {
        $routeSeparator = '/';
        $dividedMap = explode($routeSeparator, $map);
        $dividedRequestedRoute = explode($routeSeparator, $requestedRoute);

        if(count($dividedMap) !== count($dividedRequestedRoute)) {
            return false;
        }

        foreach ($dividedMap as $key => $mapFragment) {
            if(substr($mapFragment, 0, 1) === '@') {
                $request->setParameter(str_replace('@', '', $mapFragment), $dividedRequestedRoute[$key]);
            } else {
                if($mapFragment !== $dividedRequestedRoute[$key]) {
                    return false;
                }
            }
        }

        return true;
    }

    private function checkCalledControllerMethod($fullControllerName, $controllerMethod)
    {
        if (!method_exists($fullControllerName, $controllerMethod)) {
            throw new RouterException(
                'Method ' . $controllerMethod . ' doesn\'t exists in ' . $fullControllerName
            );
        }
    }
}