<?php
namespace ymi\controller;

use ymi\view\Viewer;
use ymi\utils\Helper;

/**
 * Controller for working with authorization and registration
 */
class AuthController extends BaseController {

    /**
     * authorize user
     *
     * ['login' => string, 'password' => string]
     */
    public function authorize() {

        $args = $this->registry->get('request')->getArgs();

        if (!Helper::isValid($args, ['login', 'password'])) {
            $response = [
                'template' => 'login',
                'error' => 'Please enter correct login and password'
            ];
            return $response;
        }

        $user = $this->registry->get('userModel')->getByLogin($args['login']);

        if (!$user) {
            $response = [
                'template' => 'login',
                'error' => 'Please enter correct login and password',
                'login' => $args['login']
            ];
            return $response;
        }

        if (password_verify($args['password'], $user['password'])) {

            $_SESSION['user'] = Helper::sanitizeUsersArray($user, ['password']);
            $this->registry->get('router')->redirect('/');
        }
        $response = [
            'template' => 'login',
            'error' => 'Please enter correct login and password',
            'login' => $args['login']];
        return $response;
    }

    /**
     * Registrate user
     *
     * ['login' => string. 'password' => string, 'email' => string, 'fio' => string]
     */
    public function registrate() {
        $args = $this->registry->get('request')->getArgs();
        if (!Helper::isValid($args, ['login', 'password', 'email', 'fio'])) {

            $response = [
                'template' => 'registration',
                'error' => 'Please fill all fields',
            ];
            return $response;
        }

        if (!Helper::validateEmail($args['email'])) {
            $response = [
                'template' => 'registration',
                'error' => 'Email is not correct',
            ];
            return $response;
        }
        $args['password'] = password_hash($args['password'], PASSWORD_BCRYPT);
        if (!$this->registry->get('userModel')->create($args)) {
            throw new \Exception("Can't registrate user", 1);
        }
        $this->registry->get('router')->redirect('login');
    }

    /**
     * User logout
     */
    public function logout() {
        $_SESSION = array();
        if (session_id() != "" || isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time()-2592000, '/');
        }
        session_destroy();
        $this->registry->get('router')->redirect('login');
    }

}
 ?>
