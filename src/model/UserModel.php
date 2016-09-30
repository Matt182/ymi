<?php
namespace ymi\model;

use \ymi\model\DB;
use \ymi\utils\Helper;

/**
 * User model class
 * singleton
 */
class UserModel
{
    private static $instance;

    private function __construct()
    {
        $this->db = DB::getInstance();
    }

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new UserModel();
        }
        return self::$instance;
    }

    /**
     * Gets all users
     */
    public function getAll() {
        $users = Helper::sanitizeUsersArray($this->db->getAll(), ['password']);
        return $users;
    }

    /**
     * get user by login
     *
     * @param string $lofin
     */
    public function getByLogin($login) {
        return $this->db->getByLogin($login);
    }

    /**
     * get user by id
     *
     * @param int $id
     */
    public function getById($id) {
        $user = $this->db->getById($id);
        if(!$user) {
            throw new \Exception("User doesn't exists", 400);
        }
        $user = Helper::sanitizeUsersArray($this->db->getById($id), ['password']);
        return $user;
    }

    /**
     * create new user
     *
     * @param array $args
     */
    public function create($args) {
        return $this->db->create($args) == 1;
    }

    /**
    * make gilter and order query
    *
    *
    * @param array $args
    * args = [filter = 'substring',
    *         order = [val1 => asc, val2 => desc],
    *         role = int]
    */
    public function makeFilterAndOrderQuery($args) {
        $query = 'select * from user';
        $where = '';
        if (isset($args['filter']) || isset($args['role'])) {
            $where .= " where";
            $and = '';
            if (isset($args['role'])) {
                $role = $args['role'];
                $where .= " role = $role";
                $and = ' and';
            }
            if (isset($args['filter'])) {
                $where .= $and;
                $filter = $args['filter'];
                $where .= " (fio like '%$filter%' or login like '%$filter%')";
            }
        }

        if (isset($args['login'])) {
            $args['order']['login'] = $args['login'];
        }
        if (isset($args['fio'])) {
            $args['order']['fio'] = $args['fio'];
        }

        if (isset($args['order'])) {
            $where .= ' order by';
            $count = 1;
            foreach ($args['order'] as $field => $param) {
                $where .= " $field $param";
                if (sizeof($args['order']) != $count) {
                    $where .= ',';
                    $count++;
                }
            }
        }
        $query .= $where;

        return Helper::sanitizeUsersArray($this->db->getByQuery($query), ['password']);
    }

    /**
     * Save edits of user profile
     *
     * @param array $args
     */
    public function save($args) {
        $this->db->save($args);
        return $args['id'];
    }

    /**
     * delete user
     *
     * @param array $args
     */
    public function delete($args) {
        if ($this->db->delete($args['id']) != 1) {
            throw new \Exception("Bad request", 400);
        }
    }
}
