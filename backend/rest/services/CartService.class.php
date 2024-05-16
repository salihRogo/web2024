<?php

require_once __DIR__ . '/../dao/CartDao.class.php';

class CartService {
    private $cart_dao;
    public function __construct() {
        $this->cart_dao = new CartDao();
    }
}