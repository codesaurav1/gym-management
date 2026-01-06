<?php
namespace App\controller;

use App\services\MembershipServices;

class MembershipController
{
  private MembershipServices $membershipServices;

  public function __construct()
  {
    $this->membershipServices = new MembershipServices();
  }
  public function CreateMember()
  {
    try {
      $data = json_decode(file_get_contents('php://input'), true);


      $result = $this->membershipServices->createMember($data);

      if (isset($result['success']) && $result['success'] === false) {
        http_response_code(400); // Bad Request
        echo json_encode([
          'success' => false,
          'message' => $result['message']
        ]);
        return;
      }

      http_response_code(200);
      echo json_encode([
        'success' => true,
        'message' => 'Member created successfully'
        // 'data' => $result['data'] ?? null
      ]);
    } catch (\Exception $e) {
      http_response_code(401);
      echo json_encode(["success" => false, "error" => $e->getMessage()]);
    }
  }

  public function getMember()
  {
    try {
      $data = json_decode(file_get_contents('php://input'), true);

      $result = $this->membershipServices->getMember($data);
      $resultData = $result['data'];

      if ($result['success'] && $result['success'] === false) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => $result['message']]);
        return;
      }

      http_response_code(200);
      echo json_encode([
        "success" => true,
        "data" => [
          "member" => $resultData['member_id'],
          "admin-id" => $resultData['admin_id'],
          "owner-id" => $resultData['branch_id'],
          "status" => $resultData['status'],
          "joining-date" => $resultData['join_date']
        ]
      ]);

    } catch (\Exception $e) {
      http_response_code(401);
      echo json_encode(["success" => false, "error" => $e->getMessage()]);
    }
  }

  public function getAllMember()
  {
    try {
      $result = $this->membershipServices->getAllMember();

      http_response_code(200);
      echo json_encode(['success' => true, 'data' => $result]);
      
    } catch (\Exception $e) {
      http_response_code(401);
      echo json_encode(["success" => false, "error" => $e->getMessage()]);

    }
  }
}


?>