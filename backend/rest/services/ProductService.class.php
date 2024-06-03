<?php

require_once __DIR__ . '/../dao/ProductDao.class.php';

class ProductService {
    private $product_dao;
    public function __construct() {
        $this->product_dao = new ProductDao();
    }

    public function get_products()
    {
        return $this->product_dao->get_products();
    }

    public function get_product_by_id($product_id) {
        return $this->product_dao->get_product_by_id($product_id);
    }
}