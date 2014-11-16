<?php


namespace Zet;


class Configurator
{
    private $controllerNamespace;
    private $controllerDir;
    private $errorController;

    /**
     * @return mixed
     */
    public function getControllerNamespace()
    {
        return $this->controllerNamespace;
    }

    /**
     * @param mixed $controllerNamespace
     */
    public function setControllerNamespace($controllerNamespace)
    {
        $this->controllerNamespace = $controllerNamespace;
    }

    /**
     * @return mixed
     */
    public function getControllerDir()
    {
        return $this->controllerDir;
    }

    /**
     * @param mixed $controllerDir
     */
    public function setControllerDir($controllerDir)
    {
        $this->controllerDir = $controllerDir;
    }

    /**
     * @return mixed
     */
    public function getErrorController()
    {
        return $this->errorController;
    }

    /**
     * @param mixed $errorController
     */
    public function setErrorController($errorController)
    {
        $this->errorController = $errorController;
    }
}