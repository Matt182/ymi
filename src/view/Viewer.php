<?php
namespace ymi\view;

class Viewer {
    private static $instance;

    private function __construct()
    {

    }

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new Viewer();
        }
        return self::$instance;
    }

    public function render($page, $args=[]) {
        ob_start();
        include_once "html/$page.html";
        print(ob_get_clean());
    }
}
