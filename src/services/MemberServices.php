<?php 
namespace App\services;

use App\model\MemberModel;

class MemberServices {
  private MemberModel $memberModel;

  public function __construct() {
    $this->memberModel = new MemberModel();
  }

  public function createMember($data) {
    return $this->memberModel->createMember($data);
  }
}

?>