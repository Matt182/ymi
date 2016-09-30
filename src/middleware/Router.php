<?php
namespace ymi\middleware;

/**
 * Router
 */
class Router {

    private $routes;

    private $controller;
    private $method;
    private $restriction;
    private $request;

    public function __construct($request) {
        $this->routes = json_decode(file_get_contents('config/routes.json'), true);
        $this->request = $request;
        if (!isset($this->routes[$this->request->getUrl()])) {
            throw new \Exception("Page doesn't exists", 404);
        }
        $this->checkRights();
        $this->controller = $this->routes[$this->request->getUrl()]['controller'];
        $this->method = $this->routes[$this->request->getUrl()]['method'];
        $this->restriction = $this->routes[$this->request->getUrl()]['restriction'];
    }

    public function getController() {
        return 'ymi\controller\\'.$this->controller;
    }

    public function getMethod() {
        return $this->method;
    }

    public function getRestriction() {
        return $this->restriction;
    }

    public function redirect($url, $args = []) {
        $paramString = "";
        if (count($args) > 0) {
            $paramString = '?'.http_build_query($args);
        }
        header("Location:$url$paramstring");
    }

    /**
     * Check if user aloud to path or go to login
     */
    private function checkRights() {
        session_start();
        if(!isset($_SESSION['user'])) {
            $_SESSION['user']['role'] = 0;
        }
        $restriction = $this->routes[$this->request->getUrl()]['restriction'];
        if($_SESSION['user']['role'] < $restriction) {
            $this->redirect('/login');
        }
    }
}
