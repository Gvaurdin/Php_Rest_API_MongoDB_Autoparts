<?php

use Autoparts\App\Controllers\OrderController;
use Autoparts\App\Controllers\ProductController;
use Autoparts\App\Controllers\UserController;

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

// Маршруты для продуктов
if ($uri === '/products' && $method === 'GET') {
    (new ProductController())->index();
} elseif (preg_match('/^\/product\/([a-f0-9]{24})$/', $uri, $matches) && $method === 'GET') {
    (new ProductController())->show($matches[1]);
} elseif ($uri === '/product' && $method === 'POST') {
    (new ProductController())->store();
} elseif (preg_match('/^\/product\/([a-f0-9]{24})$/', $uri, $matches) && $method === 'PUT') {
    (new ProductController())->update($matches[1]);
} elseif (preg_match('/^\/product\/([a-f0-9]{24})$/', $uri, $matches) && $method === 'DELETE') {
    (new ProductController())->delete($matches[1]);
}

