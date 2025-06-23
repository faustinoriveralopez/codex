<?php
// config.php - Configuración de la base de datos
// Configurado con tus credenciales de InfinityFree

define('DB_HOST', 'sql208.infinityfree.com'); // Host MySQL de InfinityFree
define('DB_USERNAME', 'if0_39297596'); // Tu usuario MySQL
define('DB_PASSWORD', 'Faxny73439'); // Tu contraseña MySQL
define('DB_NAME', 'if0_39297596_attendance'); // Nombre de tu base de datos (deberás crearla)

// Zona horaria
date_default_timezone_set('America/Mexico_City');

// Función para conectar a la base de datos
function getDBConnection() {
    try {
        $conn = new PDO(
            "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
            DB_USERNAME,
            DB_PASSWORD,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ]
        );
        return $conn;
    } catch(PDOException $e) {
        die("Error de conexión: " . $e->getMessage());
    }
}

// Headers CORS para permitir peticiones desde el frontend
function setCorsHeaders() {
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
}

// Manejo de peticiones OPTIONS para CORS
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    setCorsHeaders();
    exit(0);
}
?>