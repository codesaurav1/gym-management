<?php
namespace App\controller;

use App\services\MemberSubscriptionServices;
use App\middleware\TokenAccesstractor;

class MemberSubscriptonController
{
  private MemberSubscriptionServices $memberSubscriptionServices;

  private TokenAccesstractor $tokenAccesstractor;

  public function __construct()
  {
    $this->memberSubscriptionServices = new MemberSubscriptionServices();
    $this->tokenAccesstractor = new TokenAccesstractor();
  }

  public function CreateMemberSubscription($sub_id)
  {
    try {
      $data = json_decode(file_get_contents('php://input'), true);
      $MemberId = $sub_id;
      

      $result =$this->memberSubscriptionServices->CreateMemberSubscription($sub_id);

   
      if(isset($result['success']) && trim($result['success'] === false)) {
        http_response_code(400); // Bad Request
        echo json_encode([
          'success' => false,
          'message' => $result['message'],
          'data' => $result['data']
        ]);
        return;
      }
      else {
        var_dump($result);
        exit;
      }

      http_response_code(200);
      echo json_encode(["success" => true, "message" => "Member Subscription created successfully"]);
    } catch (\Exception $e) {
      http_response_code(500);
      echo json_encode(["success" => false, "error" => $e->getMessage()]);
    }
  }

  public function GetMemberSubscription()
  {
    try {
      
      $result = $this->memberSubscriptionServices->GetMemberSubscription();

      if(isset($result['success']) && trim($result['success'] === false)) {
        http_response_code(400); // Bad Request
        echo json_encode([
          'success' => false,
          'message' => $result['message']
        ]);
        return;
      }
      http_response_code(200);
      echo json_encode(["success" => true, "data" => $result]);
    } catch (\Exception $e) {
      http_response_code(500);
      echo json_encode(["success" => false, "error" => $e->getMessage()]);
    }
  }

  public function UpdateMemberSubscription()
  {
    try {
      $data = json_decode(file_get_contents('php://input'), true);
      $result = $this->memberSubscriptionServices->UpdateMemberSubscription($data);

      if(isset($result['success']) && trim($result['success'] === false)) {
        http_response_code(400); // Bad Request
        echo json_encode([
          'success' => false,
          'message' => $result['message']
        ]);
        return;
      }
      http_response_code(200);
      echo json_encode(["success" => true, "message" => "Member Subscription updated successfully"]);
    } catch (\Exception $e) {
      http_response_code(500);
      echo json_encode(["success" => false, "error" => $e->getMessage()]);
    }
  }

  public function DeleteMemberSubscription()
  {
    try {
      $MemeberId = $this->tokenAccesstractor->findUserId();
      $result = $this->memberSubscriptionServices->DeleteMemberSubscription($MemeberId);

      if(isset($result['success']) && trim($result['success'] === false)) {
        http_response_code(400); // Bad Request
        echo json_encode([
          'success' => false,
          'message' => $result['message']
        ]);
        return;
      }
      http_response_code(200);
      echo json_encode(["success" => true, "message" => "Member Subscription deleted successfully"]);
    } catch (\Exception $e) {
      http_response_code(500);
      echo json_encode(["success" => false, "error" => $e->getMessage()]);
    }
  }

}


?>