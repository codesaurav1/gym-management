<?php 
namespace App\model;

use App\Config\DB;

class MemberModel {

  private $db;
  public function __construct() {
    $this->db = DB::getConnection();
  }

  public function createMember($data) {
    $sql = "INSERT INTO members (member_id, name, email, password, role) VALUES (?, ?, ?, ?, ?)";
    $stmt = $this->db->prepare($sql);
    $stmt->bind_param("sssss", $data['member_id'], $data['name'], $data['email'], $data['password'], $data['role']);
    $result = $stmt->execute();
    return $result;
  }
}

?>