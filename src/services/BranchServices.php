<?php
namespace App\services;

use App\model\BranchModel;
use App\security\JWTServices;
use App\security\BranchJWT;
use Ramsey\Uuid\Uuid;
use App\middleware\TokenAccesstractor;

class BranchServices
{

  private BranchModel $branchModel;
  private JWTServices $jwt;
  private TokenAccesstractor $tokenAccesstractor;

  public function __construct()
  {
    $this->branchModel = new BranchModel();
    $this->jwt = new JWTServices();
    $this->tokenAccesstractor = new TokenAccesstractor();
  }

  public function createBranch(array $data): bool
  {

    $branchId = Uuid::uuid4()->toString();

    $userId = $this->tokenAccesstractor->findUserId();

    $result = $this->branchModel->crateBranch($branchId, $userId, $data['name'], $data['address'], $data['phone']);

    return $result;
  }

  public function getAllBranches()
  {
    $ownerId = $this->tokenAccesstractor->findUserId();
    
    $branches = $this->branchModel->getAllBranches();
    return $branches;
  }
}

?>