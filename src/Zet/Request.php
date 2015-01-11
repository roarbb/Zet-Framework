<?php


namespace Zet;


class Request
{
    private $parameters = array();

    public function getRequestInfo()
    {
        $out = array();

        foreach (getallheaders() as $name => $value) {
            $out[$name] = $value;
        }

        $out['request_type'] = $this->getRequestType();

        return $out;
    }

    public function getType()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    public function getCurrentUrl()
    {
        $pageURL = (@$_SERVER["HTTPS"] == "on") ? "https://" : "http://";
        if ($_SERVER["SERVER_PORT"] != "80") {
            $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
        } else {
            $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
        }

        return $pageURL;
    }

    public function getRequestedPath()
    {
        $uri = $_SERVER['REQUEST_URI'];
        $script = $_SERVER['SCRIPT_NAME'];
        $cleanUri = str_replace('/index.php/', '', $script);

        return str_replace($cleanUri, '', $uri);
    }

    public function getRequestedRoute()
    {
        $pathWithoutIndex = str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']) . '/';
        return '/' . rtrim(str_replace($pathWithoutIndex, '', $this->getRequestedPath()), '/');
    }

    /**
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @param $key
     * @param $parameter
     */
    public function setParameter($key, $parameter)
    {
        $this->parameters[$key] = $parameter;
    }

    public function name()
    {
        return get_class($this);
    }
}