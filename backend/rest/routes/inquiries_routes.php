<?php
require_once __DIR__ . '/../services/InquiriesService.class.php';

Flight::set('inquiries_service', new InquiriesService());

Flight::route('POST /inquiry', function() { 
    $data = Flight::request()->data->getData();
    $inquiry = Flight::get('inquiries_service')->add_inquiries($data);
    Flight::json(
        $inquiry
    );
});