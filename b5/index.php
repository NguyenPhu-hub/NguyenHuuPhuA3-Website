<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/app/core/Database.php';
require_once __DIR__ . '/app/core/helpers.php';
require_once __DIR__ . '/app/core/Model.php';
require_once __DIR__ . '/app/core/Controller.php';
require_once __DIR__ . '/app/models/Category.php';
require_once __DIR__ . '/app/models/Product.php';
require_once __DIR__ . '/app/controllers/ProductController.php';

$path = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
$basePath = trim(parse_url(BASE_URL, PHP_URL_PATH), '/');

if ($basePath !== '' && str_starts_with($path, $basePath)) {
    $path = trim(substr($path, strlen($basePath)), '/');
}

if ($path === '' || $path === 'product/search') {
    (new ProductController())->search();
} else {
    http_response_code(404);
    echo 'Không tìm thấy trang.';
}
