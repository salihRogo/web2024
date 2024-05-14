<?php

require_once __DIR__ . '/../dao/UserDao.class.php';

class UserService {
    private $user_dao;
    public function __construct() {
        $this->user_dao = new UserDao();
    }

    public function get_all_users() {
        return $this->user_dao->get_all_users();
    }
}