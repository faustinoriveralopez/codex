<?php
namespace App\Models;

use Core\Model;

class Document extends Model {
    protected $table = 'documents';

    public function getNextInternalFolio($year) {
        // Pattern: OPEO-YEAR-XXXX
        $sql = "SELECT count(*) as total FROM documents WHERE YEAR(created_at) = :year";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['year' => $year]);
        $res = $stmt->fetch();
        $num = $res['total'] + 1;
        return "OPEO-" . $year . "-" . str_pad($num, 5, "0", STR_PAD_LEFT);
    }

    public function getWithDetails($id) {
        $sql = "SELECT d.*,
                u.name as creator_name,
                curr_u.name as current_user_name,
                curr_a.name as current_area_name
                FROM documents d
                LEFT JOIN users u ON d.created_by = u.id
                LEFT JOIN users curr_u ON d.current_user_id = curr_u.id
                LEFT JOIN areas curr_a ON d.current_area_id = curr_a.id
                WHERE d.id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }
}
