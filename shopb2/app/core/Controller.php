<?php
class Controller
{
    protected function view(string $view, array $data = [], string|false $layout = 'main'): void
    {
        extract($data);
        $viewFile = __DIR__ . '/../views/' . $view . '.php';

        if (!file_exists($viewFile)) {
            http_response_code(404);
            exit('Không tìm thấy giao diện: ' . e($view));
        }

        $content = function () use ($viewFile, $data) {
            extract($data);
            require $viewFile;
        };

        if ($layout === false) {
            $content();
            return;
        }

        require __DIR__ . '/../views/layouts/' . $layout . '.php';
    }

    protected function redirect(string $path): void
    {
        header('Location: ' . base_url($path));
        exit;
    }
}
