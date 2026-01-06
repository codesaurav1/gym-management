<?php 
namespace App\controller;

use App\services\BranchStaffServices;

class BranchStaffController {
  private BranchStaffServices $branchStaffServices;

  public function __construct(){
    $this->branchStaffServices = new branchStaffServices();
  }

  public function CreateBranchStaff () {
    
    try {
      $data = json_decode(file_get_contents('php://input'), true);
      $this->branchStaffServices->CreateBranchStaff($data);
      
      http_response_code(200);
      echo json_encode(['message' => 'Branch staff created successfully.']);
    } catch (\Exception $e) {
      http_response_code(500);
      echo json_encode(['error' => $e->getMessage()]);
    }
  }
}

?>