<?php

require_once __DIR__ . '/BaseDao.class.php';

class ProductDao extends BaseDao {
    public function __construct() {
        parent::__construct('products');
    }

    public function get_products()
    {
        return $this->query("SELECT * FROM products", []);
    }

    public function get_product_by_id($product_id){
        return $this->query_unique(
            "SELECT * FROM products WHERE id = :id", 
            [
                'id' => $product_id
            ]
        );
    }
}