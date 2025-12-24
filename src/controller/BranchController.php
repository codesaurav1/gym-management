<?php
namespace App\controller;

use App\services\BranchServices;

class BranchController
{
   private BranchServices $branchServices;

   public function __construct()
   {
      $this->branchServices = new BranchServices();
   }

   public function createBranch()
   {
      $data = json_decode(file_get_contents('php://input'), true);
      $result = $this->branchServices->createBranch($data);

      header('Content-Type: application/json');
      echo json_encode(["success" => true, "message" => "Branch created successfully", "data" => $result]);
   }

   public function GetAllBranches()
   {
      $branches = $this->branchServices->getAllBranches();

      if (!$branches) {
         throw new \Exception("No branches found", 404);
      }
      header('Content-Type: application/json');
      echo json_encode(["success" => true, "data" => $branches]);
   }
}

?>