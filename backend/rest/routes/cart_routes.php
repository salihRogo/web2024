<?php
require_once __DIR__ . '/../services/CartService.class.php';

Flight::set('cart_service', new CartService());

Flight::route('GET /carts', function() {
    $data = Flight::get('cart_service')->get_carts();
    Flight::json(
        $data
    );
});