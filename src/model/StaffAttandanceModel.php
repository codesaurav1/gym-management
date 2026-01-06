<?php
namespace App\model;

use App\Config\DB;
use PhpParser\Node\Expr\FuncCall;

class StaffAttandanceModel
{

  protected $db;

  public function __construct()
  {
    $this->db = DB::getConnection();
  }

  public function getOpenAttandance($staffUserId, $branchId)
  {
    $sql = "SELECT * FROM staff_attendance WHERE staff_user_id = ? AND branch_id = ? AND check_out IS NULL LIMIT 1";
    $stmt = $this->db->prepare($sql);
    $stmt->bind_param("ss", $staffUserId, $branchId);
    $stmt->execute();
    $result = $stmt->get_result();
    $rows = $result->fetch_assoc();
    return $rows;
  }

  public function CheckIn($staff_attendance_id, $staff_user_id, $branch_id, $BranchAdminId, $check_in, $attendance_type)
  {
    $sql = "INSERT INTO staff_attendance (staff_attendance_id, staff_user_id, branch_id, branch_admin, attendance_type, check_in) VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = $this->db->prepare($sql);
    $stmt->bind_param("ssssss", $staff_attendance_id, $staff_user_id, $branch_id, $BranchAdminId, $attendance_type, $check_in);
    $stmt->execute();
    $result = $stmt;
    return $result;
  }

  public function CheckOut($staff_attendance_id, $branch_id, $check_out)
  {
    $sql = "UPDATE staff_attendance SET check_out = ? WHERE staff_attendance_id = ? AND branch_id = ? AND check_out IS NULL LIMIT 1";
    $stmt = $this->db->prepare($sql);
    $stmt->bind_param("sss", $check_out, $staff_attendance_id, $branch_id);
    $stmt->execute();
    $result = $stmt;
    return $result;
  }

  public function GetStaffAttendance($staffUserId, $branchId)
  {
    $sql = "SELECT * FROM staff_attendance WHERE staff_user_id = ? AND branch_id = ?";
    $stmt = $this->db->prepare($sql);
    $stmt->bind_param("ss", $staffUserId, $branchId);
    $stmt->execute();
    $result = $stmt->get_result();
    $rows = $result->fetch_all(MYSQLI_ASSOC);
    return $rows;
  }

  public function GetStaffAttendanceByBranchId($branchId)
  {
    $sql = "SELECT * FROM staff_attendance WHERE branch_id = ?";
    $stmt = $this->db->prepare($sql);
    $stmt->bind_param("s", $branchId);
    $stmt->execute();
    $result = $stmt->get_result();
    $rows = $result->fetch_all(MYSQLI_ASSOC);
    return $rows;
  }

  public function GetStaffAttendanceByStaffId($staffUserId, $AdminId) {
    $sql = "SELECT * FROM staff_attendance WHERE staff_user_id = ? AND branch_admin = ?";
    $stmt = $this->db->prepare($sql);
    $stmt->bind_param("ss", $staffUserId,$AdminId);
    $stmt->execute();
    $result = $stmt->get_result();
    $rows = $result->fetch_all(MYSQLI_ASSOC);
    return $rows;
  }

  public function GetTodayStaffAttendance($AdminId) {
    $sql = "SELECT * FROM staff_attendance WHERE branch_admin = ? AND DATE(check_in) = CURDATE() AND check_out IS NULL";
    $stmt = $this->db->prepare($sql);
    $stmt->bind_param("s", $AdminId);
    $stmt->execute();
    $result = $stmt->get_result();
    $rows = $result->fetch_all(MYSQLI_ASSOC);
    return $rows;
  }

  public function DeleteStaffAttendance($staff_attendance_id) {
    $sql = "DELETE FROM staff_attendance WHERE staff_attendance_id = ?";
    $stmt = $this->db->prepare($sql);
    $stmt->bind_param("s", $staff_attendance_id);
    $stmt->execute();
    $result = $stmt->affected_rows;
    return $result;
  }

}


?>