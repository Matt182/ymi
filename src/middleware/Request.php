<?php
namespace ymi\middleware;

use ymi\utils\Helper;

/**
 * Request handler
 */
class Request
{
    private $method;
    private $args;
    private $url;

    public function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->url = ltrim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

        if ($this->method == 'POST') {
            $args = $_POST;
        }
        if ($this->method == 'GET') {
            $args = $_GET;
        }
        $this->args = Helper::clearEmpty($args);
    }

    public function getMethod() {
        return $this->method;
    }

    public function getUrl() {
        return $this->url;
    }

    public function getArgs() {
        return $this->args;
    }
}
