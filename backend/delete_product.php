<?php 

require_once __DIR__ . '/rest/services/ProductService.class.php';

$payload = $_REQUEST['id'];

if ($payload['first_name'] == NULL || $payload['first_name'] == '') {
    header('HTTP/1.1 500 Bad Request');
    die(json_encode(['error => "You have to provide valid patient id']));
}

$product_service = new ProductService();
$product_service->delete_product_by_id($patient_id);

echo json_encode(['message' => "You have successfully deleted the new product!"]);