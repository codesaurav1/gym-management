<?php
namespace App\services;

use App\model\StaffAttandanceModel;
use App\middleware\TokenAccesstractor;
use App\utlis\UniqueId;
use App\model\BranchStaffModel;
use PHPStan\PhpDocParser\Ast\PhpDoc\ReturnTagValueNode;


class StaffAttandanceServices
{
  private StaffAttandanceModel $staffAttandanceModel;

  private TokenAccesstractor $tokenAccesstractor;

  private BranchStaffModel $branchStaffModel;

  private $uniqueId;

  public function __construct()
  {
    $this->staffAttandanceModel = new StaffAttandanceModel();
    $this->tokenAccesstractor = new TokenAccesstractor();
    $this->uniqueId = UniqueId::generateUuid();
    $this->branchStaffModel = new BranchStaffModel();
  }
  public function getCurrentTime()
  {
    date_default_timezone_set('Asia/Kolkata');
    return date("Y-m-d H:i:s");
  }

  public function getStaffAttendanceContext()
  {
    $staffAttandanceId = $this->uniqueId;
    $StaffUserId = $this->tokenAccesstractor->findUserId();
    $branchId = $this->branchStaffModel->findBranchIdByUserId($StaffUserId)['branch_id'];

    if (!$branchId) {
      return ['success' => false, 'error' => 'You are not a member of any branch.'];
    }
    return ['staffAttandanceId' => $staffAttandanceId, 'staffUserId' => $StaffUserId, 'branchId' => $branchId];
  }


  public function CheckIn($attandanceType)
  {
    $staffAttandanceId = $this->getStaffAttendanceContext()['staffAttandanceId'];
    $StaffUserId = $this->getStaffAttendanceContext()['staffUserId'];
    $branchId = $this->getStaffAttendanceContext()['branchId'];
    $BranchAdminId = $this->branchStaffModel->findBranchAdminByBranchId($branchId)['branch_admin_id'];

    if(!$BranchAdminId) {
      return ['success' => false, 'error' => 'You are not a member of any branch.'];
    }

    $CheckInTime = $this->getCurrentTime();
    $attandanceType = $attandanceType['attandance-type'];

    $Open = $this->staffAttandanceModel->getOpenAttandance($StaffUserId, $branchId);

    if ($Open) {
      return ['success' => false, 'error' => 'You are already checked in.'];
    }

    $result = $this->staffAttandanceModel->CheckIn($staffAttandanceId, $StaffUserId, $branchId, $BranchAdminId, $CheckInTime, $attandanceType);

    return ['success' => true, 'attendanceId' => $staffAttandanceId, 'message' => 'Check in successfully', 'data' => $result];
  }

  public function CheckOut()
  {
    $open = $this->staffAttandanceModel->getOpenAttandance($this->getStaffAttendanceContext()['staffUserId'], $this->getStaffAttendanceContext()['branchId']);

    if (!$open) {
      return ['success' => false, 'error' => 'You are not checked in.'];
    }

    $result = $this->staffAttandanceModel->CheckOut($open['staff_attendance_id'], $open['branch_id'], $this->getCurrentTime());

    if (!$result) {
      return ['success' => false, 'error' => 'Something went wrong.'];
    }

    return ['success' => true, 'attendanceId' => $open['staff_attendance_id'], 'message' => 'Check out successfully'];
  }

  public function GetStaffAttendance()
  {
    $result = $this->staffAttandanceModel->GetStaffAttendance($this->getStaffAttendanceContext()['staffUserId'], $this->getStaffAttendanceContext()['branchId']);

    if (!$result) {
      return ['success' => false, 'error' => 'Something went wrong.'];
    }

    return ['success' => true, 'data' => $result];
  }

  public function GetStaffAttendanceByBranchId($BranchId) {
    $result = $this->staffAttandanceModel->GetStaffAttendanceByBranchId($BranchId);

    if(!$result) {
      return ['success' => false, 'error' => 'Something went wrong.'];
    }

    return ['success' => true, 'data' => $result];
  }

  public function GetStaffAttendanceByStaffId($StaffUserId) {
    $AdminId = $this->tokenAccesstractor->findUserId();
    $result = $this->staffAttandanceModel->GetStaffAttendanceByStaffId($StaffUserId, $AdminId);


    if(!$result) {
      return ['success' => false, 'error' => 'Staff is not found.'];
    }

    return ['success' => true, 'data' => $result];
  }

  public function GetTodayStaffAttendance() {
    $AdminId = $this->tokenAccesstractor->findUserId();
    $result = $this->staffAttandanceModel->GetTodayStaffAttendance($AdminId);

    if (!$result) {
      return ['success' => false, 'error' => 'Not any staff attendance found.'];
    }

    return ['success' => true, 'data' => $result];
  }

  public function DeleteStaffAttendance($AttendanceId) {
    $AdminId = $this->tokenAccesstractor->findUserId();
    $result = $this->staffAttandanceModel->DeleteStaffAttendance($AttendanceId);

    if (!$result) {
      return ['success' => false, 'error' => 'Attendance is not found.'];
    }

    return ['success' => true, 'message' => 'Staff attendance deleted successfully.'];
  }

}

?>