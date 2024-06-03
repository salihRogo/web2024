<?php
require_once __DIR__ . '/../services/OrderService.class.php';

Flight::set('order_service', new OrderService());

/**
 * @OA\Post(
 *      path="/orders",
 *      tags={"orders"},
 *      summary="Place order",
 *      security={
 *          {"ApiKey": {}}
 *      },
 *      @OA\Response(
 *           response=200,
 *           description="Order data or error message if product does not exist or quantity is not available"
 *      ),
 *      @OA\RequestBody(
 *          description="Order data payload",
 *          @OA\JsonContent(
 *             required={"first_name","last_name", "address", "email", "phone_number", "additional_info", "total_amount"},
 *             @OA\Property(property="first_name", type="string", example="John", description="User first name"),
 *            @OA\Property(property="last_name", type="string", example="Doe", description="User last name"),
 *           @OA\Property(property="address", type="string", example="address", description="User address"),
 *          @OA\Property(property="email", type="string", example="email", description="User email"),
 *        @OA\Property(property="phone_number", type="string", example="phone_number", description="User phone number"),
 *      @OA\Property(property="additional_info", type="string", example="additional_info", description="Additional info"),
 *   @OA\Property(property="total_amount", type="number", example="total_amount", description="Total amount")
 *          )
 *      )
 * )
 */
Flight::route('POST /orders', function() { 
    $data = Flight::request()->data->getData();
    $result = Flight::get('order_service')->place_order($data);
    Flight::json(
        $result
    );
});