<?php
namespace App\services;

use App\middleware\Auth;
use App\middleware\TokenAccesstractor;
use App\model\BranchAdminModel;
use App\model\AuthModel;

class BranchAdminServices
{
  private BranchAdminModel $branchAdminModel;

  private AuthModel $authModel;

  private TokenAccesstractor $tokenAccesstractor;

  public function __construct()
  {
    $this->branchAdminModel = new BranchAdminModel();
    $this->authModel = new AuthModel();
    $this->tokenAccesstractor = new TokenAccesstractor();
  }

  public function createBranchAdmin($data)
  {

    $findEmailResponse = $this->authModel->findUserByEmail($data['email']);

    $branchAdminId = $findEmailResponse['user_id'];

    $ownerId = $this->tokenAccesstractor->findUserId();

    $result = $this->authModel->findUserById($ownerId);

    $assignedBy = $result['email'];

    $branchData = $this->branchAdminModel->findBranchId($data['branch']);
    $branchId = $branchData['branch_id'];


    $response = $this->branchAdminModel->createBranchAdmin($branchAdminId, $ownerId, $branchId, $assignedBy);

    $updateUserRole = $this->authModel->updateUserRole($branchAdminId, 'admin');

    return $branchId;

  }
}


?>