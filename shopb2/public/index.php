<?php
session_start();

require_once __DIR__ . '/../app/config/config.php';
require_once __DIR__ . '/../app/core/Database.php';
require_once __DIR__ . '/../app/core/helpers.php';
require_once __DIR__ . '/../app/core/Model.php';
require_once __DIR__ . '/../app/core/Controller.php';
require_once __DIR__ . '/../app/core/Auth.php';
require_once __DIR__ . '/../app/core/AdminController.php';
require_once __DIR__ . '/../app/models/User.php';
require_once __DIR__ . '/../app/controllers/AuthController.php';
require_once __DIR__ . '/../app/controllers/AccountController.php';
require_once __DIR__ . '/../app/controllers/admin/AdminAuthController.php';
require_once __DIR__ . '/../app/controllers/admin/AdminDashboardController.php';
require_once __DIR__ . '/../app/controllers/admin/AdminUserController.php';

$path = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
$base = trim(parse_url(BASE_URL, PHP_URL_PATH), '/');
if ($base !== '' && str_starts_with($path, $base)) {
    $path = trim(substr($path, strlen($base)), '/');
}
$segments = $path === '' ? [] : explode('/', $path);

try {
    if (count($segments) === 0) {
        $controller = new Controller();
        $controller->home = null;
        extract(['pageTitle' => 'Trang chủ']);
        $viewFile = __DIR__ . '/../app/views/home.php';
        $content = function () use ($viewFile) { require $viewFile; };
        require __DIR__ . '/../app/views/layouts/main.php';
    } elseif ($segments[0] === 'auth') {
        $c = new AuthController();
        $action = $segments[1] ?? 'login';
        if (!in_array($action, ['login','register','logout'], true)) exit('Không tìm thấy trang');
        $c->$action();
    } elseif ($segments[0] === 'account' && ($segments[1] ?? '') === 'profile') {
        (new AccountController())->profile();
    } elseif ($segments[0] === 'admin') {
        $section = $segments[1] ?? 'dashboard';
        if ($section === 'auth') {
            $c = new AdminAuthController();
            $action = $segments[2] ?? 'login';
            if (!in_array($action, ['login','logout'], true)) exit('Không tìm thấy trang');
            $c->$action();
        } elseif ($section === 'dashboard') {
            (new AdminDashboardController())->index();
        } elseif ($section === 'user') {
            $c = new AdminUserController();
            $action = $segments[2] ?? 'index';
            if (!in_array($action, ['index','role','status'], true)) exit('Không tìm thấy trang');
            $c->$action();
        } else {
            http_response_code(404);
            exit('Chức năng này sẽ được làm ở buổi tiếp theo.');
        }
    } else {
        http_response_code(404);
        exit('Không tìm thấy trang.');
    }
} catch (Throwable $e) {
    http_response_code(500);
    echo '<h3>Lỗi hệ thống</h3><p>' . e($e->getMessage()) . '</p>';
}
