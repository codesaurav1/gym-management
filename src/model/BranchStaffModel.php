<?php
namespace App\model;

use App\Config\DB;

class BranchStaffModel
{
  protected $db;
  public function __construct()
  {
    $this->db = DB::getConnection();
  }

  public function findBranchByEmail($branchName)
  {
    $stmt = $this->db->prepare("SELECT * FROM branches WHERE name = ?");
    $stmt->bind_param("s", $branchName);
    $stmt->execute();
    $result = $stmt->get_result();
    $rows = $result->fetch_assoc();

    return $rows;
  }

  public function findBranchAdminByBranchId($branchId)
  {
    $stmt = $this->db->prepare("SELECT * FROM branch_admins WHERE branch_id = ?");
    $stmt->bind_param("s", $branchId);
    $stmt->execute();
    $result = $stmt->get_result();
    $rows = $result->fetch_assoc();

    return $rows;
  }

  public function findBranchIdByUserId($userId)
  {
    $stmt = $this->db->prepare("SELECT * FROM branch_staff WHERE staff_user_id = ?");
    $stmt->bind_param("s", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $rows = $result->fetch_assoc();
    
    return $rows;
  }

  public function CreateBranchStaff($staffId, $ownerId, $branchId, $workRole, $ownerEmail)
  {
    $sql = "INSERT INTO branch_staff (staff_user_id, admin, branch_id, work_role, assigned_by) VALUES (?, ?, ?, ?, ?)";
    $stmt = $this->db->prepare($sql);
    $stmt->bind_param("sssss", $staffId, $ownerId, $branchId, $workRole, $ownerEmail);
    $result = $stmt->execute();
    return $result;

  }
}

?>