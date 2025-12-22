<?php
namespace App\middleware;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Auth
{

  public static function handle()
  {
    $header = getallheaders();
    $authHeader = $header['Authorization'] ?? '';

    if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
      http_response_code(401);
      echo json_encode(["error" => "Unauthorized"]);
      exit;
    }

    $token = substr($authHeader, 7);

    try {
      $decoded = JWT::decode($token, new Key($_ENV['JWT_SECRET'], 'HS256'));
      return $decoded; // return decoded payload for use in controller
    } catch (\Exception $e) {
      http_response_code(401);
      echo json_encode(["error" => "Invalid token"]);
      exit;
    }
  }
}

?>