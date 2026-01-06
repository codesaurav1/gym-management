<?php 
namespace App\controller;

use App\services\StaffAttandanceServices;
class StaffAttandanceController {
  private StaffAttandanceServices $staffAttandanceServices;

  public function __construct() {
    $this->staffAttandanceServices = new StaffAttandanceServices();
  }

  public function CheckIn() {
    try {
      $attandanceType = json_decode(file_get_contents('php://input'), true);
      $result = $this->staffAttandanceServices->CheckIn($attandanceType);
      
      if(isset($result['success']) && $result['success'] === false) {
        http_response_code(400); // Bad Request
        echo json_encode([
          'success' => false,
          'message' => $result['error']
        ]);
        return;
      }

      http_response_code(200);
      echo json_encode([
        'success' => true,
        'attendance_id' => $result['attendanceId'],
        'message' => $result['message']
      ]);
    } catch(\Exception $e) {
      http_response_code(500);
      echo json_encode(['error' => $e->getMessage()]);
    }
  }

  public function CheckOut() {
    try {
      $attandanceType = json_decode(file_get_contents('php://input'), true);
      $result = $this->staffAttandanceServices->CheckOut();
      
      if(isset($result['success']) && $result['success'] === false) {
        http_response_code(400); // Bad Request
        echo json_encode([
          'success' => false,
          'error' => $result['error']
        ]);
        return;
      }

      http_response_code(200);
      echo json_encode([
        'success' => true,
        'attendance_id' => $result['attendanceId'],
        'message' => $result['message']
      ]);
    } catch(\Exception $e) {
      http_response_code(500);
      echo json_encode(['error' => $e->getMessage()]);
    }
  }

  public function GetStaffAttendance() {
    try {
      $result = $this->staffAttandanceServices->GetStaffAttendance();

      if(isset($result['success']) && $result['success'] === false) {
        http_response_code(400); // Bad Request
        echo json_encode([
          'success' => false,
          'error' => $result['error']
        ]);
        return;
      }
      http_response_code(200);
      echo json_encode([
        'success' => true,
        'data' => $result['data']
      ]);
    } catch(\Exception $e) {
      http_response_code(500);
      echo json_encode(['error' => $e->getMessage()]);
    }
  }

  public function GetStaffAttendanceByBranchId($branch_id) {
    try {
      $BranchId = (string) $branch_id['branch_id'];
      $result = $this->staffAttandanceServices->GetStaffAttendanceByBranchId($BranchId);

      if(isset($result['success']) && $result['success'] === false) {
        http_response_code(400); // Bad Request
        echo json_encode([
          'success' => false,
          'error' => $result['error']
        ]);
        return;
      }
      http_response_code(200);
      echo json_encode([
        'success' => true,
        'data' => $result['data']
      ]);
    } catch(\Exception $e) {
      http_response_code(500);
      echo json_encode(['error' => $e->getMessage()]);
    }
  }
  
  public function GetStaffAttendanceByStaffId($staff_user_id) {
    try {
      $staffUserId = (string) $staff_user_id['staff_id'];
      $result = $this->staffAttandanceServices->GetStaffAttendanceByStaffId($staffUserId);

      if(isset($result['success']) && $result['success'] === false) {
        http_response_code(400); // Bad Request
        echo json_encode([
          'success' => false,
          'error' => $result['error']
        ]);
        return;
      }
      http_response_code(200);
      echo json_encode([
        'success' => true,
        'data' => $result['data']
      ]);
    } catch(\Exception $e) {
      http_response_code(500);
      echo json_encode(['error' => $e->getMessage()]);
    }
  }

  public function GetTodayStaffAttendance() {
    try {
         $result = $this->staffAttandanceServices->GetTodayStaffAttendance();

      if(isset($result['success']) && $result['success'] === false) {
        http_response_code(400); // Bad Request
        echo json_encode([
          'success' => false,
          'error' => $result['error']
        ]);
        return;
      }
      http_response_code(200);
      echo json_encode([
        'success' => true,
        'data' => $result['data']
      ]);
    } catch(\Exception $e) {
      http_response_code(500);
      echo json_encode(['error' => $e->getMessage()]);
    }
  }

  public function DeleteStaffAttendance($attendance_id) {
    try {
      $AttendanceId = (string) $attendance_id['attendance_id'];
      $result = $this->staffAttandanceServices->DeleteStaffAttendance($AttendanceId);
      
      if(isset($result['success']) && $result['success'] === false) {
        http_response_code(400); // Bad Request
        echo json_encode([
          'success' => false,
          'error' => $result['error']
        ]);
        return;
      }
      http_response_code(200);
      echo json_encode([
        'success' => true,
        'message' => $result['message']
      ]);
    } catch(\Exception $e) {
      http_response_code(500);
      echo json_encode(['error' => $e->getMessage()]);
    }
  }
}



?>