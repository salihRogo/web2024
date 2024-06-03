<?php
require_once __DIR__ . '/../services/CartService.class.php';

Flight::set('cart_service', new CartService());

Flight::route('GET /carts', function() {
    $carts = Flight::get('cart_service')->get_all_carts();
    Flight::json(
        $carts
    );
});

Flight::route('POST /carts', function() {
    $data = Flight::request()->data->getData();

    if (!isset($data['user_id'])) {
        Flight::halt(400, 'User ID is required');
    }

    $cart = Flight::get('cart_service')->init_cart($data['user_id']);
    Flight::json(
        $cart
    );
});

Flight::route('PATCH /carts/@id', function($id) {
    $result = Flight::get('cart_service')->changeIsOrdered($id);
    Flight::json(["message" => "Cart is ordered."]);
});