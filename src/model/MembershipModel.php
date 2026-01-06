<?php
namespace App\model;

use App\Config\DB;

class MembershipModel
{

  private $db;
  public function __construct()
  {
    $this->db = DB::getConnection();
  }

  public function createMember($memberId, $ownerId, $branchId)
  {
    $sql = "INSERT INTO members (member_id, admin_id, branch_id) VALUES (?, ?, ?)";
    $stmt = $this->db->prepare($sql);
    $stmt->bind_param("sss", $memberId, $ownerId, $branchId);
    $result = $stmt->execute();
    return $result;
  }

  public function getMember($adminId)
  {
    $sql = "SELECT * FROM members WHERE admin_id = ?";
    $stmt = $this->db->prepare($sql);
    $stmt->bind_param("s", $adminId);
    $stmt->execute();

    $result = $stmt->get_result();
    $rows = $result->fetch_assoc();

    return $rows;
  }

  public function getAllMember($ownerId)
  {
    $sql = "SELECT * FROM members WHERE owner_id = ? AND status = 'Active' ORDER BY join_date DESC";
    $stmt = $this->db->prepare($sql);
    $stmt->bind_param("s", $ownerId);
    $stmt->execute();

    $result = $stmt->get_result();
    $row = $result->fetch_all(MYSQLI_ASSOC);

    return $row;


  }

}

?>