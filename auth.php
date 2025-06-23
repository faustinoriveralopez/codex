<?php
// auth.php - Sistema de autenticación para el panel de administración
session_start();
require_once 'config.php';

setCorsHeaders();

$action = isset($_GET['action']) ? $_GET['action'] : '';

switch($action) {
    case 'login':
        handleLogin();
        break;
    
    case 'logout':
        handleLogout();
        break;
    
    case 'check':
        checkAuth();
        break;
    
    default:
        echo json_encode(['error' => 'Acción no válida']);
}

// Manejar inicio de sesión
function handleLogin() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(['error' => 'Método no permitido']);
        return;
    }
    
    $data = json_decode(file_get_contents('php://input'), true);
    $username = $data['username'] ?? '';
    $password = $data['password'] ?? '';
    
    if (empty($username) || empty($password)) {
        echo json_encode(['error' => 'Credenciales incompletas']);
        return;
    }
    
    $conn = getDBConnection();
    
    try {
        $stmt = $conn->prepare("SELECT * FROM admin_users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_username'] = $username;
            $_SESSION['login_time'] = time();
            
            echo json_encode([
                'success' => true,
                'message' => 'Inicio de sesión exitoso'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Credenciales inválidas'
            ]);
        }
    } catch(Exception $e) {
        echo json_encode(['error' => 'Error en el servidor']);
    }
}

// Manejar cierre de sesión
function handleLogout() {
    session_destroy();
    echo json_encode([
        'success' => true,
        'message' => 'Sesión cerrada'
    ]);
}

// Verificar autenticación
function checkAuth() {
    $isLoggedIn = isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
    
    // Verificar timeout de sesión (30 minutos)
    if ($isLoggedIn && isset($_SESSION['login_time'])) {
        $elapsed = time() - $_SESSION['login_time'];
        if ($elapsed > 1800) { // 30 minutos
            session_destroy();
            $isLoggedIn = false;
        } else {
            $_SESSION['login_time'] = time(); // Renovar tiempo
        }
    }
    
    echo json_encode([
        'authenticated' => $isLoggedIn,
        'username' => $isLoggedIn ? $_SESSION['admin_username'] : null
    ]);
}

// Función para crear hash de contraseña (usar solo para generar nuevas contraseñas)
function createPasswordHash($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

// Ejemplo de uso (descomenta para generar un nuevo hash):
// echo createPasswordHash('tunuevacontraseña');
?>