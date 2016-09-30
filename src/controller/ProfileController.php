<?php
namespace ymi\controller;

use \ymi\utils\Helper;

/**
 * Controller for profile functions
 */
class ProfileController extends BaseController {

    /**
     * Gets profile page
     *
     * ['id' => id]
     */
    public function index() {
        $args = $this->registry->get('request')->getArgs();
        if (!isset($args['id'])) {
            throw new \Exception("Bad request", 400);
        }

        $user = $this->registry->get('userModel')->getById($args['id']);

        if (!$user) {
            throw new \Exception("Error Processing Request", 2);
        }

        return [
            'template' => 'profile',
            'user' => $user
        ];
    }

    /**
     * Save changes in user profile
     */
    public function save() {
        $args = $this->registry->get('request')->getArgs();
        $id = $this->registry->get('userModel')->save($args);
        $user = $this->registry->get('userModel')->getById($id);
        return [
            'template' => 'profile',
            'user' => $user
        ];
    }

    /**
     * Delete user
     *
     * ['id' => id]
     */
    public function delete() {
        $args = $this->registry->get('request')->getArgs();
        if(!Helper::isValid($args, ['id'])) {
            throw new Exception("Bad request", 400);
        }
        $this->registry->get('userModel')->delete($args);
        $this->registry->get('router')->redirect('/', ['success' => 'user succesfully deleted']);
    }
}
