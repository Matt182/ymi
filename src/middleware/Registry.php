<?php
namespace ymi\middleware;

use \ymi\model\UserModel;
use \ymi\view\Viewer;

 /**
  * Registry for global objects
  */
class Registry {

    private $registry = [];

    public function get($key) {
        if(!$this->registry[$key]) {
            throw new \Exception("Bad request", 400);
        }
        return $this->registry[$key];
    }

    public function set($key, $val) {
        $this->registry[$key] = $val;
    }
}
