<?php
    require_once __DIR__ . '/../services/UserService.class.php';

    Flight::set('users_service', new UserService());

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

    Flight::route('GET /users', function() {
        $users = Flight::get('users_service')->get_users();
        Flight::json(
            $users
        );
    });

    Flight::route('GET /user', function() {
        $params = Flight::request()->query;

        $user = Flight::get('users_service')->get_user_by_id($params['user_id']);
        Flight::json(
            $user);
    });

    Flight::route('GET /users/current', function() {
        $current_user_id = Flight::get('user');
        $user = Flight::get('users_service')->get_user_by_id($current_user_id);
        Flight::json(
            $user
        );
    });

    Flight::route('POST /users/me', function() {
        $current_user_id = Flight::get('user');
        $data = Flight::request()->data->getData();
        
        $user = Flight::get('users_service')->update($current_user_id, $data);
        Flight::json(
            $user
        );
    });
