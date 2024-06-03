<?php

require_once __DIR__ . '/../dao/OrderDao.class.php';

class OrderService {
    private $order_dao;
    public function __construct() {
        $this->order_dao = new OrderDao();
    }

    public function place_order($payload) {
        return $this->order_dao->place_order($payload);
    }
}