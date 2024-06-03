<?php
require_once __DIR__ . '/../services/NewsletterService.class.php';

Flight::set('newsletter_service', new NewsletterService());

Flight::route('POST /newsletter', function () {
    $data = Flight::request()->data->getData();
    $newsletter = Flight::get('newsletter_service')->add_newsletters($data);
    Flight::json(
        $newsletter
    );
});