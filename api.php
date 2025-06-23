<?php
// api.php - API principal del sistema
require_once 'config.php';

setCorsHeaders();

// Obtener la acción solicitada
$action = isset($_GET['action']) ? $_GET['action'] : '';

switch($action) {
    case 'check_attendance':
        checkAttendance();
        break;
    
    case 'get_stats':
        getStats();
        break;
    
    case 'get_attendance_report':
        getAttendanceReport();
        break;
    
    case 'get_employees':
        getEmployees();
        break;
    
    case 'export_report':
        exportReport();
        break;
    
    default:
        echo json_encode(['error' => 'Acción no válida']);
}

// Registrar entrada/salida
function checkAttendance() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(['error' => 'Método no permitido']);
        return;
    }
    
    $data = json_decode(file_get_contents('php://input'), true);
    $employeeId = strtoupper($data['employeeId'] ?? '');
    $employeeName = $data['employeeName'] ?? '';
    
    if (empty($employeeId) || empty($employeeName)) {
        echo json_encode(['error' => 'Datos incompletos']);
        return;
    }
    
    $conn = getDBConnection();
    
    try {
        // Verificar si el empleado existe, si no, crearlo
        $stmt = $conn->prepare("INSERT IGNORE INTO employees (id, name) VALUES (?, ?)");
        $stmt->execute([$employeeId, $employeeName]);
        
        // Verificar si ya hay un registro para hoy
        $today = date('Y-m-d');
        $stmt = $conn->prepare("SELECT * FROM attendance WHERE employee_id = ? AND date = ?");
        $stmt->execute([$employeeId, $today]);
        $record = $stmt->fetch();
        
        if ($record) {
            if ($record['check_out'] === null) {
                // Registrar salida
                $currentTime = date('H:i:s');
                $stmt = $conn->prepare("UPDATE attendance SET check_out = ? WHERE id = ?");
                $stmt->execute([$currentTime, $record['id']]);
                
                echo json_encode([
                    'success' => true,
                    'type' => 'checkout',
                    'message' => "Salida registrada para $employeeName a las " . date('H:i'),
                    'time' => date('H:i')
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Ya se registró entrada y salida para hoy'
                ]);
            }
        } else {
            // Registrar entrada
            $currentTime = date('H:i:s');
            $stmt = $conn->prepare("INSERT INTO attendance (employee_id, date, check_in) VALUES (?, ?, ?)");
            $stmt->execute([$employeeId, $today, $currentTime]);
            
            echo json_encode([
                'success' => true,
                'type' => 'checkin',
                'message' => "Entrada registrada para $employeeName a las " . date('H:i'),
                'time' => date('H:i')
            ]);
        }
    } catch(Exception $e) {
        echo json_encode(['error' => 'Error en el servidor: ' . $e->getMessage()]);
    }
}

// Obtener estadísticas
function getStats() {
    $conn = getDBConnection();
    $today = date('Y-m-d');
    
    try {
        // Total de empleados activos
        $stmt = $conn->query("SELECT COUNT(*) as total FROM employees WHERE active = 1");
        $totalEmployees = $stmt->fetch()['total'];
        
        // Presentes hoy
        $stmt = $conn->prepare("SELECT COUNT(DISTINCT employee_id) as present FROM attendance WHERE date = ?");
        $stmt->execute([$today]);
        $presentToday = $stmt->fetch()['present'];
        
        // Calcular ausentes y porcentaje
        $absentToday = max(0, $totalEmployees - $presentToday);
        $attendanceRate = $totalEmployees > 0 ? round(($presentToday / $totalEmployees) * 100) : 0;
        
        echo json_encode([
            'totalEmployees' => $totalEmployees,
            'presentToday' => $presentToday,
            'absentToday' => $absentToday,
            'attendanceRate' => $attendanceRate
        ]);
    } catch(Exception $e) {
        echo json_encode(['error' => 'Error al obtener estadísticas']);
    }
}

// Obtener reporte de asistencias
function getAttendanceReport() {
    $period = $_GET['period'] ?? 'daily';
    $date = $_GET['date'] ?? date('Y-m-d');
    
    $conn = getDBConnection();
    
    try {
        $query = "";
        $params = [];
        
        switch($period) {
            case 'daily':
                $query = "SELECT a.*, e.name as employee_name 
                         FROM attendance a 
                         JOIN employees e ON a.employee_id = e.id 
                         WHERE a.date = ? 
                         ORDER BY a.date DESC, a.check_in DESC";
                $params = [$date];
                break;
                
            case 'weekly':
                $weekStart = date('Y-m-d', strtotime('monday this week', strtotime($date)));
                $weekEnd = date('Y-m-d', strtotime('sunday this week', strtotime($date)));
                $query = "SELECT a.*, e.name as employee_name 
                         FROM attendance a 
                         JOIN employees e ON a.employee_id = e.id 
                         WHERE a.date BETWEEN ? AND ? 
                         ORDER BY a.date DESC, a.check_in DESC";
                $params = [$weekStart, $weekEnd];
                break;
                
            case 'monthly':
                $monthStart = date('Y-m-01', strtotime($date));
                $monthEnd = date('Y-m-t', strtotime($date));
                $query = "SELECT a.*, e.name as employee_name 
                         FROM attendance a 
                         JOIN employees e ON a.employee_id = e.id 
                         WHERE a.date BETWEEN ? AND ? 
                         ORDER BY a.date DESC, a.check_in DESC";
                $params = [$monthStart, $monthEnd];
                break;
        }
        
        $stmt = $conn->prepare($query);
        $stmt->execute($params);
        $records = $stmt->fetchAll();
        
        // Calcular estadísticas del período
        $uniqueDates = array_unique(array_column($records, 'date'));
        $uniqueEmployees = array_unique(array_column($records, 'employee_id'));
        
        $summary = [
            'totalRecords' => count($records),
            'uniqueDates' => count($uniqueDates),
            'uniqueEmployees' => count($uniqueEmployees),
            'completeRecords' => count(array_filter($records, function($r) { 
                return $r['check_in'] && $r['check_out']; 
            })),
            'incompleteRecords' => count(array_filter($records, function($r) { 
                return $r['check_in'] && !$r['check_out']; 
            }))
        ];
        
        echo json_encode([
            'records' => $records,
            'summary' => $summary
        ]);
    } catch(Exception $e) {
        echo json_encode(['error' => 'Error al obtener reporte']);
    }
}

// Obtener lista de empleados
function getEmployees() {
    $conn = getDBConnection();
    
    try {
        $stmt = $conn->query("SELECT * FROM employees WHERE active = 1 ORDER BY name");
        $employees = $stmt->fetchAll();
        
        echo json_encode(['employees' => $employees]);
    } catch(Exception $e) {
        echo json_encode(['error' => 'Error al obtener empleados']);
    }
}

// Exportar reporte (devuelve datos para CSV)
function exportReport() {
    // Reutilizar la función getAttendanceReport
    getAttendanceReport();
}
?>