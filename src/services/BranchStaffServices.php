<?php
namespace App\services;

use App\middleware\TokenAccesstractor;
use App\model\AuthModel;
use App\model\BranchModel;
use App\model\BranchStaffModel;


class BranchStaffServices
{
  private BranchStaffModel $branchStaffModel;
  private AuthModel $authModel;

  private BranchModel $branchModel;
  private TokenAccesstractor $tokenAccesstractor;

  public function __construct()
  {
    $this->branchStaffModel = new BranchStaffModel();
    $this->authModel = new AuthModel();
    $this->branchModel = new BranchModel();
    $this->tokenAccesstractor = new TokenAccesstractor();
  }

  public function CreateBranchStaff($data)
  {
    $staffEmail = $data['email'];
    $workRole = $data['work-role'];
    $branch = $data['branch'];

    $staffId = $this->authModel->findUserByEmail($staffEmail)['user_id'];

    $ownerId = $this->tokenAccesstractor->findUserId();
    $ownerEmail = $this->authModel->findUserById($ownerId)['email'];

    $branchId = $this->branchStaffModel->findBranchByEmail($branch)['branch_id'];

    $result = $this->branchStaffModel->CreateBranchStaff($staffId, $ownerId, $branchId, $workRole, $ownerEmail);

    $updateUserRole = $this->authModel->updateUserRole($staffId, 'staff');
    return true;
  }
}


?>