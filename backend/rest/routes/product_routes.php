<?php

require_once __DIR__ . '/../services/ProductService.class.php';

Flight::set('products_service', new ProductService());

Flight::route('GET /products', function() {
    $data = Flight::get('products_service')->get_products();
    Flight::json(
        $data
    );
});
