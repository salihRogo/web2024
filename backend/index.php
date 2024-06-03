<?php

require 'vendor/autoload.php';

require 'rest/routes/middleware_routes.php';
require 'rest/routes/product_routes.php';
require 'rest/routes/cart_routes.php';
require 'rest/routes/inquiries_routes.php';
require 'rest/routes/newsletter_routes.php';
require 'rest/routes/user_routes.php';
require 'rest/routes/cart_products_routes.php';
require 'rest/routes/order_routes.php';
require 'rest/routes/auth_routes.php';

Flight::start();