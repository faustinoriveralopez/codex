<?php
namespace App\Models;

use Core\Model;

class OutgoingDocument extends Model {
    protected $table = 'outgoing_documents';

    public function getNextFolio($year) {
        // Get sequence from settings or calc from table?
        // Let's use a settings table for explicit control as requested ("posibilidad de modificar el inicio")

        // 1. Check settings
        $stmt = $this->db->query("SELECT value FROM settings WHERE key_name = 'outgoing_sequence_" . $year . "'");
        $res = $stmt->fetch();

        $num = 1;
        if ($res) {
            $num = (int)$res['value'];
        } else {
            // Init setting if not exists
            $this->db->query("INSERT INTO settings (key_name, value) VALUES ('outgoing_sequence_" . $year . "', 1)");
        }

        // 2. Format
        $folio = "OPEO-SAL-" . $year . "-" . str_pad($num, 5, "0", STR_PAD_LEFT);

        return ['folio' => $folio, 'number' => $num];
    }

    public function incrementSequence($year, $currentNum) {
        $next = $currentNum + 1;
        $stmt = $this->db->prepare("UPDATE settings SET value = :val WHERE key_name = :key");
        $stmt->execute(['val' => $next, 'key' => 'outgoing_sequence_' . $year]);
    }
}
