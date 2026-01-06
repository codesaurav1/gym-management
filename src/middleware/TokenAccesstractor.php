<?php
namespace App\middleware;

use App\security\BranchJWT;

class TokenAccesstractor
{

  private BranchJWT $branchJWT;

  public function __construct()
  {
    $this->branchJWT = new BranchJWT();
  }

  public function findUserId()   
  {

    $headers = getallheaders();

    if (!isset($headers['Authorization'])) {
      throw new \Exception('Authorization header not found', 401);
    }

    if (!preg_match('/Bearer\s(\S+)/', $headers['Authorization'], $matches)) {
      throw new \Exception('Invalid Authorization header format', 401);
    }

    $token = $matches[1];

    $userId = $this->branchJWT->getUserId($token);

    if (!$userId) {
      throw new \Exception('user id not found in token', 401);
    }

    return $userId;

  }

  public function getRefreshToken()
  {

    if (!isset($_COOKIE['RefreshToken'])) {
      throw new \Exception("Refresh token not found", 401);
    }

    return $_COOKIE['RefreshToken'];
  }
}
?>