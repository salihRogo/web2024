<?php
require_once __DIR__ . '/../services/NewsletterService.class.php';

Flight::set('newsletter_service', new NewsletterService());

Flight::route('GET /newsletters', function() {
    $data = Flight::get('cart_service')->get_newsletters();
    Flight::json(
        $data
    );
});