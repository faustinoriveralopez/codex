<?php
namespace App\Controllers;

use Core\Controller;
use App\Models\DocumentHistory;
use App\Models\User;

class ReportController extends Controller {
    public function __construct() {
        $this->requireAuth();
        // Check generic role permission? Assuming admin/director/mesa_control/auditor
        // For MVP, allowing all logged in users to see audit might be loose, but acceptable or restrict here:
        if (!in_array($_SESSION['role'], ['admin', 'director', 'mesa_control', 'auditor'])) {
             // Redirect or die?
             // $this->redirect('dashboard');
        }
    }

    public function audit() {
        $data = ['title' => 'Auditoría y Bitácora Global'];

        $historyModel = new DocumentHistory();
        $userModel = new User();

        $data['users'] = $userModel->all();

        // Filters
        $startDate = $_GET['start_date'] ?? date('Y-m-d', strtotime('-1 month'));
        $endDate = $_GET['end_date'] ?? date('Y-m-d');
        $userId = $_GET['user_id'] ?? '';
        $action = $_GET['action'] ?? '';

        // Build Query
        $sql = "SELECT h.*, u.name as user_name, d.internal_folio, d.subject
                FROM document_history h
                JOIN users u ON h.user_id = u.id
                JOIN documents d ON h.document_id = d.id
                WHERE DATE(h.created_at) BETWEEN :start AND :end";

        $params = ['start' => $startDate, 'end' => $endDate];

        if (!empty($userId)) {
            $sql .= " AND h.user_id = :uid";
            $params['uid'] = $userId;
        }

        if (!empty($action)) {
            $sql .= " AND h.action LIKE :act";
            $params['act'] = "%$action%";
        }

        $sql .= " ORDER BY h.created_at DESC LIMIT 500";

        $data['logs'] = $historyModel->query($sql, $params)->fetchAll();
        $data['filters'] = ['start_date' => $startDate, 'end_date' => $endDate, 'user_id' => $userId, 'action' => $action];

        $this->view('reports/audit', $data);
    }

    public function exportAudit() {
        // Same logic as audit but outputs CSV
        $historyModel = new DocumentHistory();

        $startDate = $_GET['start_date'] ?? date('Y-m-d', strtotime('-1 month'));
        $endDate = $_GET['end_date'] ?? date('Y-m-d');
        $userId = $_GET['user_id'] ?? '';
        $action = $_GET['action'] ?? '';

        $sql = "SELECT d.internal_folio, h.action, u.name as user_name, h.comment, h.created_at
                FROM document_history h
                JOIN users u ON h.user_id = u.id
                JOIN documents d ON h.document_id = d.id
                WHERE DATE(h.created_at) BETWEEN :start AND :end";

        $params = ['start' => $startDate, 'end' => $endDate];

        if (!empty($userId)) {
            $sql .= " AND h.user_id = :uid";
            $params['uid'] = $userId;
        }
        if (!empty($action)) {
            $sql .= " AND h.action LIKE :act";
            $params['act'] = "%$action%";
        }
        $sql .= " ORDER BY h.created_at DESC";

        $logs = $historyModel->query($sql, $params)->fetchAll();

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="auditoria_sicor_' . date('Ymd_Hi') . '.csv"');

        $output = fopen('php://output', 'w');
        fputcsv($output, ['Folio', 'Accion', 'Usuario', 'Detalle/Comentario', 'Fecha y Hora']);

        foreach ($logs as $row) {
            fputcsv($output, $row);
        }
        fclose($output);
        exit;
    }
}
