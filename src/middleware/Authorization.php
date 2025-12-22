<?php
namespace App\middleware;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Authorization {
  public static function handle(array $allowedRoles = []) {
    $headers = getallheaders();
    $authHeader = $headers['Authorization'] ?? null;

    if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
      http_response_code(401);
      echo json_encode(["error" => "Unauthorized"]);
      exit;
    }

    $token = substr($authHeader, 7);

    try {
      $decoded = JWT::decode($token, new key($_ENV['JWT_SECRET'], 'HS256'));

      // If no role restrictions allow 
      if (empty($allowedRoles)) {
        return true;
      }

      // Check if user role is allowed 
      if (!in_array($decoded->role, $allowedRoles)) {
        http_response_code(403);
        echo json_encode(["error" => "Forbidden: insufficient permissions"]);
        exit;
      }

      return true;
    } catch (\Exception $e) {
      http_response_code(401);
      echo json_encode(["error" => "Invalid token"]);
      exit;
    }
  }
}
 
?>