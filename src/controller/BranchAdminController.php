<?php
namespace App\controller;

use App\services\BranchAdminServices;
class BranchAdminController
{
  private BranchAdminServices $branchAdminServices;

  public function __construct()
  {
    $this->branchAdminServices = new BranchAdminServices();
  }

  public function createBranchAdmin()
  {

    try {
      $data = json_decode(file_get_contents('php://input'), true);

      $result = $this->branchAdminServices->createBranchAdmin($data);

      http_response_code(200);
      echo json_encode(["success" => true, "message" => "Branch Admin created"]);

    } catch (\Exception $e) {
      http_response_code(401);
      echo json_encode(["success" => false, "message" => "Branch Admin not created ." . $e->getMessage()]);
    }
  }

}


?>