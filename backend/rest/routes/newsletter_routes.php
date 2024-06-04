<?php
require_once __DIR__ . '/../services/NewsletterService.class.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

Flight::set('newsletter_service', new NewsletterService());


/**
 * @OA\Post(
 *      path="/newsletter",
 *      tags={"newsletter"},
 *      summary="Subscribe to newsletter",
 *      security={
 *         {"ApiKey": {}}
 *      },
 *      @OA\Response(
 *           response=200,
 *           description="Subscribe to newsletter"
 *      ),
 *     @OA\RequestBody(
 *     description="Newsletter data payload",
 *     @OA\JsonContent(
 *         required={"email"},
 *         @OA\Property(property="email", type="string", example="example@example.com"),
 * )
 *
 * )
 * )
 */
Flight::route('POST /newsletter', function () {
    $data = Flight::request()->data->getData();
    $newsletter = Flight::get('newsletter_service')->add_newsletters($data);
    Flight::json(
        $newsletter
    );
});