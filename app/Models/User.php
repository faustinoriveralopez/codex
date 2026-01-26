<?php
namespace App\Models;

use Core\Model;

class User extends Model {
    protected $table = 'users';

    public function findByEmail($email) {
        // We join to get role slug and area name
        $sql = "SELECT u.*, r.slug as role_slug, a.name as area_name
                FROM users u
                JOIN roles r ON u.role_id = r.id
                JOIN areas a ON u.area_id = a.id
                WHERE u.email = :email";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['email' => $email]);
        return $stmt->fetch();
    }
}
