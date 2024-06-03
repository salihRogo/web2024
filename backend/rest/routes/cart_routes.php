<?php
require_once __DIR__ . '/../services/CartService.class.php';

Flight::set('cart_service', new CartService());

/**
 * @OA\Get(
 *      path="/carts",
 *      tags={"carts"},
 *      summary="Get all carts",
 *      security={
 *          {"ApiKey": {}}
 *      },
 *      @OA\Response(
 *           response=200,
 *           description="Get all carts"
 *      )
 * )
 */
Flight::route('GET /carts', function() {
    $carts = Flight::get('cart_service')->get_all_carts();
    Flight::json(
        $carts
    );
});

/**
 * @OA\Post(
 *     path="/carts",
 *    tags={"carts"},
 *   summary="Create a cart",
 * security={
 *    {"ApiKey": {}}
 * },
 * @OA\Response(
 *    response=200,
 *   description="Create a cart"
 * ),
 * @OA\RequestBody(
 *   description="Cart data payload",
 *  @OA\JsonContent(
 *     required={"user_id"},
 *   @OA\Property(property="user_id", type="number", example="1"),
 * )
 * )
 * )
 */
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

/**
 * @OA\Patch(
 *     path="/carts/{id}",
 *     tags={"carts"},
 *     summary="Change is ordered",
 *     security={
 *       {"ApiKey": {}}
 *     },
 *     @OA\Response(
 *       response=200,
 *       description="Cart is ordered"
 *     ),
 *     @OA\Parameter(
 *       @OA\Schema(type="number"), 
 *       in="path", 
 *       name="id", 
 *       example="1", 
 *       description="Cart ID"
 *     )
 * )
 */
Flight::route('PATCH /carts/@id', function($id) {
    $result = Flight::get('cart_service')->changeIsOrdered($id);
    Flight::json(["message" => "Cart is ordered."]);
});