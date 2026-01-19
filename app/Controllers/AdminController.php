<?php
namespace App\Controllers;

use Core\Controller;
use App\Models\User;
use App\Models\Area;
use App\Models\ClosingType;

class AdminController extends Controller {
    public function __construct() {
        $this->requireAuth();
        if ($_SESSION['role'] !== 'admin') {
            $this->redirect('dashboard');
        }
    }

    public function index() {
        $this->view('admin/index', ['title' => 'Administración del Sistema']);
    }

    // --- Closing Types CRUD ---
    public function closingTypes() {
        $model = new ClosingType();
        $types = $model->all();
        $this->view('admin/closing_types', ['types' => $types, 'title' => 'Catálogo: Tipos de Cierre']);
    }

    public function storeClosingType() {
        $name = $_POST['name'] ?? '';
        if ($name) {
            $model = new ClosingType();
            $model->create(['name' => $name]);
        }
        $this->redirect('admin/closing_types');
    }

    // --- Areas CRUD ---
    public function areas() {
        $model = new Area();
        $areas = $model->all();
        $this->view('admin/areas', ['areas' => $areas, 'title' => 'Catálogo: Áreas']);
    }

    public function storeArea() {
        $name = $_POST['name'] ?? '';
        $code = $_POST['code'] ?? '';
        if ($name && $code) {
            $model = new Area();
            $model->create(['name' => $name, 'code' => $code]);
        }
        $this->redirect('admin/areas');
    }

    // --- Users (Simple List for now) ---
    public function users() {
        $model = new User();
        $users = $model->all(); // Note: password is in results, view should not display it
        $this->view('admin/users', ['users' => $users, 'title' => 'Usuarios del Sistema']);
    }
}
