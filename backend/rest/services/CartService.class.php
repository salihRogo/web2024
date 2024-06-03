<?php

require_once __DIR__ . '/../dao/CartDao.class.php';

class CartService {
    private $cart_dao;
    public function __construct() {
        $this->cart_dao = new CartDao();
    }

    public function get_all_carts() {
        return $this->cart_dao->get_all_carts();
    }

    public function init_cart($user_id) {
        return $this->cart_dao->init_cart($user_id);
    }

    public function changeIsOrdered($id) {
        return $this->cart_dao->changeIsOrdered($id);
    }
}