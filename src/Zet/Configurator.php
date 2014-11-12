<?php


namespace Zet;


class Configurator
{
    private $controllerNamespace;
    private $controllerDir;

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
}