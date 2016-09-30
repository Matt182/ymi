<?php
namespace ymi\model;

use PDO;

class DB
{
    private $connection;
    private static $instance = null;

    private function __construct()
    {
        $dsn = getenv('driver') . ":dbname=" . getenv('dbname') . ";host=" . getenv('host');
        $dbusername = getenv('username');
        $dbpassword = getenv('password');
        try{
            $this->connection = new PDO($dsn, $dbusername, $dbpassword);
        } catch(PDOException $e) {
            throw new \Exception($e->getMessage);
        }
    }

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new DB();
        }
        return self::$instance;
    }

    public function getAll() {
        $stmt = $this->connection->query("select * from user");
        if (!$stat) {
            throw new Exception("Internal database error", 3);
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getByLogin($login) {
        $stmt = $this->connection->query("select * from user where login = '$login'");
        if (!$stat) {
            throw new Exception("Internal database error", 3);
        }
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->connection->query("select * from user where id = $id");
        if (!$stat) {
            throw new Exception("Internal database error", 3);
        }
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($args) {
        $login = $args['login'];
        $password = $args['password'];
        $email = $args['email'];
        $fio = $args['fio'];
        $role = $args['role'];
        $stmt = $this->connection->exec("insert into user (login, password, email, fio, role) values ('$login', '$password', '$email', '$fio', $role)");
        if ($stmt === false) {
            throw new Exception("Internal database error", 3);
        }
        return $stmt;
    }

    public function getByQuery($query) {
        $stmt = $this->connection->query($query);
        if (!$stat) {
            throw new Exception("Internal database error", 3);
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function save($args) {
        $id = $args['id'];
        unset($args['id']);
        $query = 'update user set ';
        $count = 1;
        foreach ($args as $key => $value) {
            $query .= "$key='$value'";
            if ($count < count($args)) {
                $query .= ', ';
                $count++;
            }
        }
        $query .= " where id=$id";
        $stmt = $this->connection->exec($query);
        if ($stmt === false) {
            throw new Exception("Internal database error", 3);
        }
        return $stmt;
    }

    public function delete($id) {
        $query = "delete from user where id=$id";
        $stmt = $this->connection->exec($query);
        if ($stmt === false) {
            throw new Exception("Internal database error", 3);
        }
        return $stmt;
    }
}
