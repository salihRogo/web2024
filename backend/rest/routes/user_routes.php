<?php
    require_once __DIR__ . '/../services/UserService.class.php';

    use Firebase\JWT\JWT;
    use Firebase\JWT\Key;

    Flight::set('users_service', new UserService());

    /**
     * @OA\Post(
     *      path="/users",
     *      tags={"users"},
     *      summary="Add user data to database",
     *      @OA\Response(
     *           response=200,
     *           description="User data, or error message if email already exists or password and confirm password do not match"
     *      ),
     *     @OA\RequestBody(
     *          description="User data payload",
     *          @OA\JsonContent(
     *             required={"first_name", "last_name", "email", "password", "passwordconfirm"},
     *             @OA\Property(property="first_name", type="string", example="John", description="User first name"),
     *             @OA\Property(property="last_name", type="string", example="Doe", description="User last name"),
     *             @OA\Property(property="email", type="string", example="example@example.com", description="User email"),
     *             @OA\Property(property="password", type="string", example="password", description="User password"),
     *             @OA\Property(property="password_confirm", type="string", example="password", description="User password")
     * )
     * )
     * )
     */
    Flight::route('POST /users', function() {
        $data = Flight::request()->data->getData();

        if (!isset($data['email']) || !isset($data['password']) || !isset($data['password_confirm'])) {
            Flight::halt(400, 'Email, password and confirm password are required');
        }

        if ($data['password'] !== $data['password_confirm']) {
            Flight::halt(400, 'Password and confirm password do not match');
        }
        
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        unset($data['password_confirm']);

        $user = Flight::get('users_service')->add_user($data);
        Flight::json(
            $user
        );
    });

    /**
     * @OA\Get(
     *      path="/users",
     *      tags={"users"},
     *      summary="Get all users",
     *      security={
     *          {"ApiKey": {}}
     *      },
     *      @OA\Response(
     *           response=200,
     *           description="Get all users"
     *      )
     * )
     */
    Flight::route('GET /users', function() {
        $users = Flight::get('users_service')->get_users();
        Flight::json(
            $users
        );
    });

    /**
     * @OA\Get(
     *      path="/user",
     *      tags={"users"},
     *      summary="Get user by id",
     *      security={
     *          {"ApiKey": {}}
     *      },
     *      @OA\Response(
     *           response=200,
     *           description="User data or false if user does not exist"
     *      ),
     *      @OA\Parameter(@OA\Schema(type="number"), in="query", name="user_id", example="1", description="User ID")
     * )
     */
    Flight::route('GET /user', function() {
        $params = Flight::request()->query;

        $user = Flight::get('users_service')->get_user_by_id($params['user_id']);
        Flight::json(
            $user);
    });

    /**
     * @OA\Get(
     *      path="/users/current",
     *      tags={"users"},
     *      summary="Get current user",
     *      security={
     *          {"ApiKey": {}}
     *      },
     *      @OA\Response(
     *           response=200,
     *           description="Get current user"
     *      )
     * )
     */
    Flight::route('GET /users/current', function() {
        $current_user_id = Flight::get('user');
        $user = Flight::get('users_service')->get_user_by_id($current_user_id);
        Flight::json(
            $user
        );
    });

    /**
     * @OA\Post(
     *      path="/users/me",
     *      tags={"users"},
     *      summary="Update current user information",
     *      security={
     *          {"ApiKey": {}}
     *      },
     *      @OA\Response(
     *           response=200,
     *           description="Update current user"
     *      ),
     *  @OA\RequestBody(
     *          description="User data payload",
     *          @OA\JsonContent(
     *             @OA\Property(property="id", type="string", example="1", description="User id"),
     *             @OA\Property(property="first_name", type="string", example="John", description="User first name"),
     *             @OA\Property(property="last_name", type="string", example="Doe", description="User last name"),
     *             @OA\Property(property="mobile_number", type="number", example="1233456", description="User phone number"),
     *             @OA\Property(property="address_line1", type="string", example="address", description="User address"),
     *             @OA\Property(property="address_line2", type="string", example="address2", description="User address2"),
     *             @OA\Property(property="city", type="string", example="city", description="User city"),
     *             @OA\Property(property="zip_code", type="number", example="12345", description="User zip code")
     * 
     * )
     * )
     * )
     */
    Flight::route('POST /users/me', function() {
        $current_user_id = Flight::get('user');
        $data = Flight::request()->data->getData();
        
        $user = Flight::get('users_service')->update($current_user_id, $data);
        Flight::json(
            $user
        );
    });
