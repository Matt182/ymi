<?php
namespace ymi\controller;

use \ymi\view\Viewer;

/**
 * Controller for working with simple pages for non auth users
 */
class PageController extends BaseController {

    /**
     * Gets login page
     */
    public function login() {
        $this->checkCredentials();
        return ['template' => 'login'];
    }

    /**
     * Gets registration page
     */
    public function registration() {
        $this->checkCredentials();
        return ['template' => 'registration'];
    }

    /**
     * check if user is already auth
     */
    private function checkCredentials() {
        if ($this->registry->get('router')->getRestriction() < $_SESSION['user']['role']) {
            $this->registry->get('router')->redirect('/');
        }
    }
}
