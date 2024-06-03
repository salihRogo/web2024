<?php

require_once __DIR__ . '/../services/ProductService.class.php';

Flight::set('products_service', new ProductService());

/**
 * @OA\Get(
 *      path="/products",
 *      tags={"products"},
 *      summary="Get all products",
 *      security={
 *          {"ApiKey": {}}
 *      },
 *      @OA\Response(
 *           response=200,
 *           description="Get all products"
 *      )
 * )
 */
Flight::route('GET /products', function() {
    $data = Flight::get('products_service')->get_products();
    Flight::json(
        $data
    );
});

/**
 * @OA\Get(
 *      path="/products/{product_id}",
 *      tags={"products"},
 *      summary="Get product by id",
 *      security={
 *          {"ApiKey": {}}
 *      },
 *      @OA\Response(
 *           response=200,
 *           description="Product data or false if product does not exist"
 *      ),
 *      @OA\Parameter(@OA\Schema(type="number"), in="path", name="product_id", example="1", description="Product ID")
 * )
 */
Flight::route('GET /products/@product_id', function($product_id) {
    $product = Flight::get('products_service')->get_product_by_id($product_id);
    Flight::json($product);
});
