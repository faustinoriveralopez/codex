<?php
namespace Core;

class Controller {
    public function view($view, $data = []) {
        extract($data);
        $viewPath = 'app/Views/' . $view . '.php';

        if (file_exists($viewPath)) {
            require $viewPath;
        } else {
            die("View file not found: $viewPath");
        }
    }

    public function redirect($url) {
        // Handle absolute or relative
        if (strpos($url, 'http') === 0) {
            header("Location: " . $url);
        } else {
            $target = rtrim(BASE_URL, '/') . '/' . ltrim($url, '/');
            header("Location: " . $target);
        }
        exit;
    }

    protected function requireAuth() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('login');
        }
    }

    protected function json($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}
