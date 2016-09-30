<?php
namespace ymi\controller;

use ymi\model\UserModel;
use ymi\view\Viewer;
use ymi\utils\Helper;

/**
 * Controller for user list
 */
class UserListController extends BaseController
{
    /**
     * Gets user list
     */
    public function index() {
        $users = $this->registry->get('userModel')->getAll();
        return [
            'template' => 'main',
            'users' => $users,
        ];
    }

    /**
     * filter and order for user list
     *
     * []
     */
    public function filterAndOrder() {
        $args = $this->registry->get('request')->getArgs();
        $users = $this->registry->get('userModel')->makeFilterAndOrderQuery($args);
        return [
            'template' => 'userList',
            'users' => $users,
        ];
    }

    /**
     * Creates new user
     *
     * ['login' => string, 'password' => string, 'email' => string, 'fio' => string, 'role' => string]
     */
    public function create() {
        $users = $this->registry->get('userModel')->getAll();
        $args = $this->registry->get('request')->getArgs();

        if (!Helper::isValid($args, ['login', 'password', 'email', 'fio', 'role'])) {

            return [
                'template' => 'userList',
                'error' => 'Please fill all fields',
                'users' => $users,
            ];
        }


        if (!Helper::validateEmail($args['email'])) {
            return [
                'template' => 'userList',
                'error' => 'Email is not correct',
                'users' => $users,
            ];
        }
        $args['password'] = password_hash($args['password'], PASSWORD_BCRYPT);
        if (!$this->registry->get('userModel')->create($args)) {
            throw new \Exception("Interanll error", 2);
        }

        $users = $this->registry->get('userModel')->getAll();

        return [
            'template' => 'userList',
            'success'=> 'User succesfully created',
            'users' => $users,
        ];
    }
}
