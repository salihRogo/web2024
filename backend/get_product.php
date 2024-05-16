<?php

require_once __DIR__ . '/rest/services/ProductServices.class.php';

$product_id = $_REQUEST['id'];

$product_service = new ProductService();
$product = $product_service->get_product_by_id($product_id);

header('Content-Type: application/json');
echo json_encode($product);