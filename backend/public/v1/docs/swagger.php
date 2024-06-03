<?php

require __DIR__ . '/../../../vendor/autoload.php';

if($_SERVER['SERVER_NAME'] == 'localhost' || $_SERVER['SERVER_NAME'] == '127.0.0.1') {
    define('BASE_URL', 'http://localhost:8888/project/backend/');
}
else {
    define('BASE_URL', 'https://squid-app-dksi9.ondigitalocean.app/backend/');

}

error_reporting(0);

$openapi = \OpenApi\Generator::scan(['../../../rest/routes', './'], ['pattern' => '*.php']);
header('Content-Type: application/x-yaml');
echo $openapi->toYaml();
?>
