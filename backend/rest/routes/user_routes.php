<?php
require_once __DIR__ . '/../services/UserService.class.php';

Flight::set('user_service', new UserService());

Flight::route('GET /users', function() {
    $data = Flight::get('user_service')->get_all_users();
    Flight::json(
        $data
    );
});