<?php

use OpenApi\Logger\ConsoleLogger;

require_once __DIR__ . '/BaseDao.class.php';

class CartProductsDao extends BaseDao {
    public function __construct() {
        parent::__construct('cart_products');
    }

    public function find_cart_id($user_id) {
        $result = $this->query_unique("SELECT c.id FROM carts c WHERE c.user_id = :user_id AND c.is_ordered = 0", ["user_id" => $user_id]);
        return $result;
    }

    public function add_cart_products($payload) {
        $user_id = $payload['user_id'];
        $cart_id = $this->find_cart_id($user_id);
        $payload['cart_id'] = $cart_id['id'];
        $query = 'INSERT INTO cart_products (cart_id, product_id, quantity) VALUES (:cart_id, :product_id, :quantity)';
        $payload = ["cart_id" => $payload['cart_id'], "product_id" => $payload['product_id'], "quantity" => $payload['quantity']];
        return $this->query($query, $payload);
    }

    public function delete_cart_products($id) {
        $result = $this->query("DELETE FROM cart_products WHERE id = :id", ["id" => $id]);
        return $result;
    }

    public function get_cart_products($cart_id) {
        $result = $this->query("SELECT cp.id as 'id', 
                                    cp.quantity as 'quantity', 
                                    cp.product_id as 'product_id', 
                                    p.name as 'name', 
                                    p.description as 'description', 
                                    p.price as 'price', 
                                    p.image as 'image',
                                    c.user_id as 'user_id'
                             FROM carts c
                             JOIN cart_products cp ON c.id = cp.cart_id
                             JOIN products p ON p.id = cp.product_id
                             WHERE c.is_ordered = 0 AND c.id = :cart_id", ['cart_id' => $cart_id]);
        return $result;
    }

    public function increase_quantity($id) {
        $result = $this->query("UPDATE cart_products SET quantity = quantity + 1 WHERE id = :id", ["id" => $id]);
        return $result;
    }

    public function decrease_quantity($id) {
        $result = $this->query("UPDATE cart_products SET quantity = quantity - 1 WHERE id = :id", ["id" => $id]);
        return $result;
    }
}
