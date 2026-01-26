<?php
namespace App\Controllers;

use Core\Controller;
use App\Models\User;

class AuthController extends Controller {
    public function login() {
        if (isset($_SESSION['user_id'])) {
            $this->redirect('dashboard');
        }
        $this->view('auth/login');
    }

    public function attemptLogin() {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
             $this->view('auth/login', ['error' => 'Por favor ingrese correo y contraseña']);
             return;
        }

        $userModel = new User();
        $user = $userModel->findByEmail($email);

        if ($user) {
            // Check MD5 (for seeded users)
            if (md5($password) === $user['password']) {
                // Login Success
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['role'] = $user['role_slug'];
                $_SESSION['role_id'] = $user['role_id'];
                $_SESSION['area_id'] = $user['area_id'];
                $_SESSION['area_name'] = $user['area_name'];

                $this->redirect('dashboard');
            } else {
                $this->view('auth/login', ['error' => 'Contraseña incorrecta']);
            }
        } else {
            $this->view('auth/login', ['error' => 'Usuario no encontrado']);
        }
    }

    public function logout() {
        session_destroy();
        $this->redirect('login');
    }
}
