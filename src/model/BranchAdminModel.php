<?php
namespace App\model;

use App\Config\DB;

class BranchAdminModel
{

  protected $db;

  public function __construct()
  {
    $this->db = DB::getConnection();
  }

  public function findBranchId($branchName)
  {
    $stmt = $this->db->prepare("SELECT * FROM branches WHERE name = ?");
    $stmt->bind_param('s', $branchName);
    $stmt->execute();
    $result = $stmt->get_result();
    $rows = $result->fetch_assoc();

    return $rows;
  }

  public function findOwnerIdByAdminId($adminId)
  {
    $sql = "SELECT * FROM branch_admins WHERE branch_admin_id = ?";
    $stmt = $this->db->prepare($sql);
    $stmt->bind_param("s", $adminId);
    $stmt->execute();
    $result = $stmt->get_result();
    $rows = $result->fetch_assoc();

    return $rows;
  }

  public function createBranchAdmin($branchAdminId, $ownerId, $branchId, $assignedBy)
  {
    $sql = "INSERT INTO branch_admins (branch_admin_id, branch_id, owner_id, assigned_by)
    VALUE (?, ?, ?, ?)";

    $stmt = $this->db->prepare($sql);
    $stmt->bind_param("ssss", $branchAdminId, $branchId, $ownerId, $assignedBy);
    $stmt->execute();
    $result = $stmt->get_result();

    return $result;
  }

}

?>