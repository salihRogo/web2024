<?php

require_once __DIR__ . '/rest/services/ProductService.class.php';

$payload = $_REQUEST;

if ($payload['first_name'] == NULL || $payload['first_name'] == '') {
    header('HTTP/1.1 500 Bad Request');
    die(json_encode(['error => "First name field is missing']));
}

$product_service = new ProductService();

if($payload['id'] != NULL && $payload['id'] != '') {
    $product = $product_service->edit_product($payload);
} else {
    unset($payload['id']);
    $product = $product_service->add_product($payload);
}

echo json_encode(['message' => "You have successfully added the new product!"]);
