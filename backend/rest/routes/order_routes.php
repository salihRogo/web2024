<?php
require_once __DIR__ . '/../services/OrderService.class.php';

Flight::set('order_service', new OrderService());

Flight::route('POST /orders', function() { 
    $data = Flight::request()->data->getData();
    $result = Flight::get('order_service')->place_order($data);
    Flight::json(
        $result
    );
});