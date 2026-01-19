<?php
namespace App\Controllers;

use Core\Controller;
use App\Models\Document;
use App\Helpers\DateHelper;

class DashboardController extends Controller {
    public function __construct() {
    }

    public function index() {
        $this->requireAuth();

        $docModel = new Document();

        // 1. Update Semaphores for Active Documents
        // In a real app, this should be a cron job, but here we run it on dashboard load for the MVP
        $this->updateSemaphores($docModel);

        // 2. Fetch Stats
        $userId = $_SESSION['user_id'];
        $areaId = $_SESSION['area_id'];
        $role = $_SESSION['role'];

        // Base Query Condition
        $whereClause = "status != 'CERRADO' AND status != 'CANCELADO'";
        $params = [];

        // If not admin/director, limit to area
        if ($role !== 'admin' && $role !== 'director') {
            $whereClause .= " AND (current_area_id = :areaId OR current_user_id = :userId)";
            $params['areaId'] = $areaId;
            $params['userId'] = $userId;
        }

        // Count Totals
        $sqlTotal = "SELECT COUNT(*) as total FROM documents WHERE $whereClause";
        $totalPending = $docModel->query($sqlTotal, $params)->fetch()['total'];

        // Count Expired (Red)
        $sqlRed = "SELECT COUNT(*) as total FROM documents WHERE $whereClause AND alert_level = 'ROJO'";
        $totalRed = $docModel->query($sqlRed, $params)->fetch()['total'];

        // Count Warning (Yellow)
        $sqlYellow = "SELECT COUNT(*) as total FROM documents WHERE $whereClause AND alert_level = 'AMARILLO'";
        $totalYellow = $docModel->query($sqlYellow, $params)->fetch()['total'];

        // Fetch Critical List (Red/Yellow)
        $sqlCritical = "SELECT * FROM documents WHERE $whereClause AND (alert_level = 'ROJO' OR alert_level = 'AMARILLO') ORDER BY deadline_date ASC LIMIT 5";
        $criticalDocs = $docModel->query($sqlCritical, $params)->fetchAll();

        $data = [
            'user' => $_SESSION,
            'title' => 'Tablero Principal',
            'stats' => [
                'pending' => $totalPending,
                'red' => $totalRed,
                'yellow' => $totalYellow
            ],
            'criticalDocs' => $criticalDocs
        ];
        $this->view('dashboard/index', $data);
    }

    private function updateSemaphores($docModel) {
        // Fetch all active docs to update their status
        $sql = "SELECT id, deadline_date FROM documents WHERE status != 'CERRADO' AND status != 'CANCELADO'";
        $docs = $docModel->query($sql)->fetchAll();

        foreach ($docs as $doc) {
            $newStatus = DateHelper::getSemaphoreStatus($doc['deadline_date']);
            $docModel->update($doc['id'], ['alert_level' => $newStatus]);
        }
    }
}
