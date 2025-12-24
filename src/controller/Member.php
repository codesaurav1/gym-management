<?php 
namespace App\controller;

use App\services\MemberServices;

class Member {
  private MemberServices $memberServicesl;

  public function __constract() {
    $this->memberServices = new MemberServices();
  }

  public function createMember() {
    $data = json_decode(file_get_contents('php://input'), true);
    $result = $this->memberServices->createMember($data);
    header('Content-Type: application/json');
    echo json_encode(["success" => true, "message" => "Member created successfully", "data" => $result]);
  }
}

?>