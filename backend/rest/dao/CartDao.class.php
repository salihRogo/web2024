<?php

require_once __DIR__ . '/BaseDao.class.php';

class CartDao extends BaseDao {
    public function __construct() {
        parent::__construct('carts');
    }

    public function get_all_carts() {
        return $this->query("SELECT * FROM carts", []);
    }

    public function init_cart($user_id) {
        $cart = [
            "user_id" => $user_id
        ];
        $query = 'INSERT INTO carts (user_id)
                  SELECT :user_id
                  WHERE NOT EXISTS (
                    SELECT 1 
                    FROM carts 
                    WHERE user_id = :user_id AND is_ordered = 0
                 )';
        return $this->query($query, $cart);
    }

    public function changeIsOrdered($id) {
        $query = 'UPDATE carts SET is_ordered = 1 WHERE id = :id';
        return $this->query($query, ["id" => $id]);
    }
}