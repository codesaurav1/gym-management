<?php
namespace App\services;

use App\model\MembershipModel;
use App\middleware\TokenAccesstractor;
use Exception;
use Ramsey\Uuid\Uuid;
use App\model\AuthModel;
use App\model\BranchStaffModel;
use App\model\BranchModel;
use App\model\BranchAdminModel;

class MembershipServices
{
  private MembershipModel $membershipModel;
  private TokenAccesstractor $tokenAccesstractor;
  private AuthModel $authModel;
  private BranchStaffModel $branchStaffModel;
  private BranchModel $branchModel;
  private BranchAdminModel $branchAdminModel;

  public function __construct()
  { 
  
    $this->membershipModel = new MembershipModel();
    $this->tokenAccesstractor = new TokenAccesstractor();
    $this->authModel = new AuthModel();
    $this->branchStaffModel = new BranchStaffModel();
    $this->branchModel = new BranchModel();
    $this->branchAdminModel = new BranchAdminModel();
  }

  public function createMember($data)
  {

    $user = $this->authModel->findUserByEmail($data['email']);
    if ($user === null) {
      return ['success' => false, 'message' => 'User not found with email: ' . $data['email']];
    }


    $memberId = $user['user_id']; // Get the user id from the user

    $ownerId = $this->tokenAccesstractor->findUserId();

    $branchData = $this->branchStaffModel->findBranchByEmail($data['branch']);

    $branchId = $branchData['branch_id'];

    // Check if the user is the owner or admin of the branch

    $branchStaffAdminData = $this->branchStaffModel->findBranchAdminByBranchId($branchId);
    $branchStaffAdminId = $branchStaffAdminData['branch_admin_id'];
    $branchStaffOwnerId = $branchStaffAdminData['owner_id'];

    if ($branchStaffAdminId != $ownerId && $branchStaffOwnerId != $ownerId) {
      return ['success' => false, 'message' => 'You are not authorized to add members to this branch'];
    }

    $result = $this->membershipModel->createMember($memberId, $ownerId, $branchId);

    return $result;

  }

  public function getMember($data)
  {
    $adminId = $this->tokenAccesstractor->findUserId();

    // Check the requested user is owner or admin of the branch
    $branchAdminData = $this->branchAdminModel->findOwnerIdByAdminId($adminId);

    if ($branchAdminData['branch_admin_id'] != $adminId && $branchAdminData['owner_id'] != $adminId) {
      return ['success' => false, 'message' => 'You are not authorized to view this member'];
    }

    // Get member data from admin branch
    $memberData = $this->membershipModel->getMember($adminId);

    return ['success' => true, 'data' => $memberData];
  }

  public function getAllMember()
  {
    $ownerId = $this->tokenAccesstractor->findUserId();

    // Find which branch member data owner can view
    $data = $this->membershipModel->getAllMember($ownerId);
    return $data;
  }
}

?>