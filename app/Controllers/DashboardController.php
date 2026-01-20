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

        // --- KPI Data for Charts ---
        // 1. Monthly Stats (Received vs Closed) for current year
        $chartMonthly = [
            'labels' => ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
            'received' => array_fill(0, 12, 0),
            'closed' => array_fill(0, 12, 0)
        ];

        $sqlMonthly = "SELECT MONTH(created_at) as m, COUNT(*) as c FROM documents WHERE YEAR(created_at) = YEAR(CURDATE()) GROUP BY m";
        $resRec = $docModel->query($sqlMonthly)->fetchAll();
        foreach($resRec as $r) $chartMonthly['received'][$r['m']-1] = $r['c'];

        $sqlMonthlyClosed = "SELECT MONTH(updated_at) as m, COUNT(*) as c FROM documents WHERE status = 'CERRADO' AND YEAR(updated_at) = YEAR(CURDATE()) GROUP BY m";
        $resClosed = $docModel->query($sqlMonthlyClosed)->fetchAll();
        foreach($resClosed as $r) $chartMonthly['closed'][$r['m']-1] = $r['c'];

        // 2. Compliance (On Time vs Late)
        // Late = Closed documents where updated_at > deadline_date OR Active documents with Red status
        // For simplicity: Just check current active semaphores + closed history if we had it.
        // Let's stick to current snapshot: Green/Yellow (On Time) vs Red (Late)
        $chartCompliance = [
            'on_time' => $totalPending + $totalYellow, // Approximation
            'late' => $totalRed
        ];

        // 3. Workload by Area (Active Docs)
        $sqlArea = "SELECT a.code, COUNT(d.id) as total FROM documents d JOIN areas a ON d.current_area_id = a.id WHERE d.status != 'CERRADO' GROUP BY a.code";
        $resArea = $docModel->query($sqlArea)->fetchAll();
        $chartArea = [
            'labels' => [],
            'data' => []
        ];
        foreach($resArea as $r) {
            $chartArea['labels'][] = $r['code'];
            $chartArea['data'][] = $r['total'];
        }

        $data = [
            'user' => $_SESSION,
            'title' => 'Tablero Principal',
            'stats' => [
                'pending' => $totalPending,
                'red' => $totalRed,
                'yellow' => $totalYellow
            ],
            'criticalDocs' => $criticalDocs,
            'charts' => [
                'monthly' => $chartMonthly,
                'compliance' => $chartCompliance,
                'area' => $chartArea
            ]
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
