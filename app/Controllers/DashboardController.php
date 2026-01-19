<?php
namespace App\Controllers;

use Core\Controller;

class DashboardController extends Controller {
    public function __construct() {
        // Check auth in constructor not possible directly in this simple framework
        // because router instantiates then calls method.
        // So we call check in the method or use a base controller hook.
        // For now, call in index.
    }

    public function index() {
        $this->requireAuth();

        $data = [
            'user' => $_SESSION,
            'title' => 'Tablero Principal'
        ];
        $this->view('dashboard/index', $data);
    }
}
