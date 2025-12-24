<?php
namespace App\security;

use App\services\BranchServices;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class BranchJWT
{

  private string $secert;

  public function __construct()
  {
    $this->secert = $_ENV['JWT_SECRET'];
  }

  // Decode token and return the full object
  public function decodeToken(string $token): object
  {
    try {
      $decoded = JWT::decode($token, new Key($this->secert, 'HS256'));
    } catch (\Exception $e) {
      throw new \Exception('Invalid token: ' . $e->getMessage());
    }

    return $decoded; // ✅ full object
  }

  // Safely get user ID
  public function getUserId(string $token)
  {
    $decoded = $this->decodeToken($token);

    if (isset($decoded->user_id))
      return ($decoded->user_id); // return (int) $decoded->user_id;
    if (isset($decoded->id))
      return $decoded->id;
    if (isset($decoded->sub))
      return $decoded->sub;

    throw new \Exception('User ID not found in token');
  }
}

?>