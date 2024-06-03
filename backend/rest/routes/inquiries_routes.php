<?php
require_once __DIR__ . '/../services/InquiriesService.class.php';

Flight::set('inquiries_service', new InquiriesService());

Flight::route('POST /inquiries', function() { 
    $data = Flight::request()->data->getData();
    $inquiries = Flight::get('inquiries_service')->add_inquiries($data);
    Flight::json(
        $inquiries
    );
});