<?php
class Controller
{
    protected function view(string $file, array $data = []): void
    {
        extract($data);
        $viewFile = __DIR__ . '/../views/' . $file . '.php';

        if (!file_exists($viewFile)) {
            http_response_code(404);
            die('Không tìm thấy giao diện.');
        }

        $content = function () use ($viewFile, $data) {
            extract($data);
            require $viewFile;
        };

        require __DIR__ . '/../views/layouts/main.php';
    }
}
