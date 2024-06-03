<?php

require_once __DIR__ . '/BaseDao.class.php';

class UserDao extends BaseDao {
    public function __construct() {
        parent::__construct('users');
    }

    public function add_user($payload) {
        return $this->insert('users', $payload);
    }

    public function get_user_by_id($user_id) {
        return $this->query_unique("SELECT * FROM users WHERE id = :id", ["id" => $user_id]);
    }

    public function update_user($user_id, $user) {
        $this->update("users", $user_id, $user);
    }

    public function update($table, $id, $entity, $id_column = "id")
    {
        $query = "UPDATE {$table} SET ";
        foreach ($entity as $name => $value) {
        $query .= $name . "= :" . $name . ", ";
        }
        $query = substr($query, 0, -2);
        $query .= " WHERE {$id_column} = :id";
        $stmt = $this->connection->prepare($query);
        $entity['id'] = $id;
        $stmt->execute($entity);
    }
    public function get_all_users() {
        return $this->query("SELECT * FROM users", []);
    }
}