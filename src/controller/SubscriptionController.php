<?php
namespace App\Controller;

use App\model\SubscriptionModel;
use App\services\SubscriptionServices;

class SubscriptionController
{

  private SubscriptionServices $subscriptionServices;

  public function __construct()
  {
    $this->subscriptionServices = new SubscriptionServices();
  }

  public function CreateSubscription()
  {

    try {

      $data = json_decode(file_get_contents("php://input"), true);
      $result =$this->subscriptionServices->CreateSubscription($data);

      if(isset($result['success']) && $result['success'] === false) {
        http_response_code(400); // Bad Request
        echo json_encode([
          'success' => false,
          'message' => $result['message'],
          'data' => $result['data']
        ]);
        return;
      }
      http_response_code(200);
      echo json_encode(["success" => true, "message" => "Subscription created successfully"]);
    } catch (\Exception $e) {
      http_response_code(500);
      echo json_encode(["success" => false, "error" => $e->getMessage()]);
    }
  }

  public function GetSubscription()
  {

    try {
      $result = $this->subscriptionServices->GetSubscription();

      http_response_code(200);
      echo json_encode(["success" => true, "data" => $result]);
    } catch (\Exception $e) {
      http_response_code(500);
      echo json_encode(["success" => false, "error" => $e->getMessage()]);
    }
  }

  public function UpdateSubscription()
  {
    try {
      $sub_id = "30d33424-df8a-4752-98ea-5849bc8924f7";
      $data = json_decode(file_get_contents("php://input"), true);
      $result = $this->subscriptionServices->UpdateSubcription($sub_id, $data);

      if (isset($result['success']) && $result['success'] === false) {
        http_response_code(400); // Bad Request
        echo json_encode([
          'success' => false,
          'message' => $result['message']
        ]);
        return;
      }

      http_response_code(200);
      echo json_encode(["success" => true, "affected" => $result]);
    } catch (\Exception $e) {
      http_response_code(500);
      echo json_encode(["success" => false, "error" => $e->getMessage()]);
    }
  }

  public function DeleteSubscription()
  {
    try {
      $sub_id = "30d33424-df8a-4752-98ea-5849bc8924f7";
      $result = $this->subscriptionServices->DeleteSubscription($sub_id);

      if (isset($result['success']) && $result['success'] === false) {
        http_response_code(400); // Bad Request
        echo json_encode([
          'success' => false,
          'message' => $result['message']
        ]);
        return;
      }

      http_response_code(200);
      echo json_encode(["success" => true, "message" => "Subscription deleted successfully"]);
    } catch (\Exception $e) {
      http_response_code(500);
      echo json_encode(["success" => false, "error" => $e->getMessage()]);
    }
  }
}