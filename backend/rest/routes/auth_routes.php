<?php
    require_once __DIR__ . '/../../config.php';
    require_once __DIR__ . '/../services/AuthService.class.php';

    use Firebase\JWT\JWT;
    use Firebase\JWT\Key;

    Flight::set('auth_service', new AuthService());

    /**
     * @OA\Post(
     *      path="/login",
     *      tags={"auth"},
     *      summary="Login to system",
     *      @OA\Response(
     *           response=200,
     *           description="User data and JWT token"
     *      ),
     *      @OA\RequestBody(
     *          description="User credentials",
     *          @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", required=true, type="string", example="admin@user.com"),
     *             @OA\Property(property="password", required=true, type="string", example="pass")
     *           )
     *      ),
     * )
     */
    Flight::route('POST /login', function() {
        $payload = Flight::request()->data->getData();

        $user = Flight::get('auth_service')->get_user_by_email($payload['email']);

        if(!$user || !password_verify($payload['password'], $user['password']))
            Flight::halt(500, "Invalid username or password");

        unset($user['password']);
        
        $jwt_payload = [
            'user' => $user,
            'iat' => time(),
            // If this parameter is not set, JWT will be valid for life. This is not a good approach
            'exp' => time() + (60 * 60) // valid for an hour
        ];

        $token = JWT::encode(
            $jwt_payload,
            Config::JWT_SECRET(),
            'HS256'
        );

        Flight::json(
            array_merge($user, ['token' => $token])
        );
    });
    ;

