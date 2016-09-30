<?php
namespace ymi\controller;

/**
 * Base class for controllers
 */
class BaseController {

    protected $registry;

    public function __construct($registry) {
        $this->registry = $registry;
    }
}
 ?>
