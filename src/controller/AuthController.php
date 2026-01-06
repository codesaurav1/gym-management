<?php
namespace App\controller;

use App\services\AuthServices;

class AuthController
{

  private AuthServices $authServices;

  public function __construct()
  {
    $this->authServices = new AuthServices();
  }


  public function signup()
  {
    $data = json_decode(file_get_contents('php://input'), true);

    $result = $this->authServices->signup($data);

    echo json_encode(["success" => true] + $result);
  }

  public function login()
  {

    try {
      $data = json_decode(file_get_contents('php://input'), true);
      $tokens = $this->authServices->login($data);

      echo json_encode(["success" => true, "tokens" => $tokens]);
    } catch (\Exception $e) {
      http_response_code($e->getCode() ?: 400);
      echo json_encode(["success" => false, "error" => $e->getMessage()]);
    }
  }

  public function ForgetPassword()
  {
    try{
      $data = json_decode(file_get_contents('php://input'), true);
      $result = $this->authServices->ForgetPassword($data);

      if(!isset($result['success']) || $result['success'] === false) {
        http_response_code(400);
        echo json_encode($result);
      }

      http_response_code(200);
      echo json_encode($result);
    }catch(\Exception $e) {
      http_response_code($e->getCode() ?: 400);
      echo json_encode(["success" => false, "error" => $e->getMessage()]);
    }
  }

  public function generateAccessToken()
  {
    try {
      $tokens = $this->authServices->generateAccessToken();

      http_response_code(200);
      echo json_encode(["success" => true, "tokens" => $tokens]);
    } catch (\Exception $e) {
      http_response_code($e->getCode() ?: 401);
      echo json_encode(["success" => false, "error" => $e->getMessage()]);
    }

  }

}

?>