<?php
require_once __DIR__ . '/../services/CartProductsService.class.php';

Flight::set('cart_products_service', new CartProductsService());

/**
 * @OA\Post(
 *     path="/cart_products",
 *     tags={"cart_products"},
 *     summary="Add a product to cart",
 *     security={
 *         {"ApiKey": {}}
 *     },
 *     @OA\Response(
 *         response=200,
 *         description="Add a product to cart"
 *     ),
 *     @OA\RequestBody(
 *         description="Cart product data payload",
 *         @OA\JsonContent(
 *             required={"cart_id", "product_id", "quantity"},
 *             @OA\Property(property="cart_id", type="number", example="1"),
 *             @OA\Property(property="product_id", type="number", example="1"),
 *             @OA\Property(property="quantity", type="number", example="1"),
 *         )
 *     )
 * )
 */
Flight::route('POST /cart_products', function() {
    $data = Flight::request()->data->getData();
    $result = Flight::get('cart_products_service')->add_cart_products($data);
    Flight::json($result);
});

/**
 * @OA\Delete(
 *     path="/cart_products/{id}",
 *     tags={"cart_products"},
 *     summary="Delete a product from cart",
 *     security={
 *         {"ApiKey": {}}
 *     },
 *     @OA\Response(
 *         response=200,
 *         description="Delete a product from cart"
 *     ),
 *     @OA\Parameter(
 *         @OA\Schema(type="number"), 
 *         in="path", 
 *         name="id", 
 *         example="1", 
 *         description="Cart product ID"
 *     )
 * )
 
 */
Flight::route('DELETE /cart_products/@id', function($id) {
    $result = Flight::get('cart_products_service')->delete_cart_products($id);
    Flight::json(["message" => "Selected cart product is deleted."]);
});


/**
 * @OA\Get(
 *      path="/cart_products",
 *      tags={"cart_products"},
 *      summary="Get all cart products",
 *      security={
 *          {"ApiKey": {}}
 *      },
 *      @OA\Response(
 *           response=200,
 *           description="Get all cart products"
 *      )
 * )
 */
Flight::route('GET /cart_products', function() {
    if (!isset($_GET['cart_id'])) {
        Flight::halt(400, 'cart_id is required');
        return;
    }

    $cart_id = $_GET['cart_id'];
    $data = Flight::get('cart_products_service')->get_cart_products($cart_id);
    Flight::json($data);
});

/**
 * @OA\Put(
 *     path="/cart_products/{id}",
 *     tags={"cart_products"},
 *     summary="Increase quantity of a product in cart",
 *     security={
 *       {"ApiKey": {}}
 *     },
 *     @OA\Response(
 *       response=200,
 *       description="Increase quantity of a product in cart"
 *     ),
 *     @OA\Parameter(
 *       @OA\Schema(type="number"), 
 *       in="path", 
 *       name="id", 
 *       example="1", 
 *       description="Cart product ID"
 *     )
 * )
 */
Flight::route('PUT /cart_products/@id', function($id) {
    $result = Flight::get('cart_products_service')->increase_quantity($id);
    Flight::json(["message" => "Quantity is increased."]);
});

/**
 * @OA\Patch(
 *     path="/cart_products/{id}",
 *     tags={"cart_products"},
 *     summary="Decrease quantity of a product in cart",
 *     security={
 *       {"ApiKey": {}}
 *     },
 *     @OA\Response(
 *       response=200,
 *       description="Decrease quantity of a product in cart"
 *     ),
 *     @OA\Parameter(
 *       @OA\Schema(type="number"), 
 *       in="path", 
 *       name="id", 
 *       example="1", 
 *       description="Cart product ID"
 *     )
 * )
 */
Flight::route('PATCH /cart_products/@id', function($id) {
    $result = Flight::get('cart_products_service')->decrease_quantity($id);
    Flight::json(["message" => "Quantity is decreased."]);
});
