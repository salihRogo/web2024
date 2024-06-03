<?php

require_once __DIR__ . '/../dao/CartProductsDao.class.php';

class CartProductsService {
    private $cart_products_dao;

    public function __construct() {
        $this->cart_products_dao = new CartProductsDao();
    }

    public function add_cart_products($payload) {
        $result = $this->cart_products_dao->add_cart_products($payload);
        return $result;
    }

    public function delete_cart_products($id) {
        $result = $this->cart_products_dao->delete_cart_products($id);
        return $result;
    }

    public function get_cart_products($cart_id) {
        $data = $this->cart_products_dao->get_cart_products($cart_id);
        return $data;
    }

    public function increase_quantity($id) {
        $result = $this->cart_products_dao->increase_quantity($id);
        return $result;
    }

    public function decrease_quantity($id) {
        $result = $this->cart_products_dao->decrease_quantity($id);
        return $result;
    }
}
