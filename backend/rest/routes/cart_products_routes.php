<?php
require_once __DIR__ . '/../services/CartProductsService.class.php';

Flight::set('cart_products_service', new CartProductsService());

Flight::route('POST /cart_products', function() {
    $data = Flight::request()->data->getData();
    $result = Flight::get('cart_products_service')->add_cart_products($data);
    Flight::json($result);
});

Flight::route('DELETE /cart_products/@id', function($id) {
    $result = Flight::get('cart_products_service')->delete_cart_products($id);
    Flight::json(["message" => "Selected cart product is deleted."]);
});

Flight::route('GET /cart_products', function() {
    if (!isset($_GET['cart_id'])) {
        Flight::halt(400, 'cart_id is required');
        return;
    }

    $cart_id = $_GET['cart_id'];
    $data = Flight::get('cart_products_service')->get_cart_products($cart_id);
    Flight::json($data);
});

Flight::route('PUT /cart_products/@id', function($id) {
    $result = Flight::get('cart_products_service')->increase_quantity($id);
    Flight::json(["message" => "Quantity is increased."]);
});

Flight::route('PATCH /cart_products/@id', function($id) {
    $result = Flight::get('cart_products_service')->decrease_quantity($id);
    Flight::json(["message" => "Quantity is decreased."]);
});
