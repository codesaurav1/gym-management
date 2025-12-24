<?php
namespace App\security;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTServices
{
  private string $secert;
  public function __construct()
  {
    $this->secert = $_ENV['JWT_SECRET'];
  }

  public function generateTokens(array $user)
  {
    $accessPayload = [
      "iat" => time(),
      "exp" => time() + (60 * 60),
      "user_id" => $user['user_id'],  
      "firstname" => $user['first_name'],
      "lastname" => $user['last_name'],
      "email" => $user['email'],
      "phone" => $user['phone'],
      "role" => $user['role'], // or "member"
      "type" => "access"
    ];

    $refreshPayload = [
      "iat" => time(),
      "exp" => time() + (7 * 24 * 60 * 60),
      "sub" => $user['user_id'],
      "type" => "refresh"
    ];

    $accessToken = JWT::encode($accessPayload, $this->secert, 'HS256');
    $refreshToken = JWT::encode($refreshPayload, $this->secert, 'HS256');

    return [
      "access_token" => $accessToken,
      "refresh_token" => $refreshToken
    ];
  }

  public function findUserId($access_token)
  {

    $payload = JWT::decode($access_token, new Key($this->secert, 'HS256'));

    return $payload->id;
  }

  public function verifyRefreshToken(string $refresh_token)
  {
    $payload = JWT::decode($refresh_token, new Key($this->secert, 'HS256'));

    if ($payload->type !== "refresh") {
      throw new \Exception("Invalid token type", 401);
    }

    return $payload;
  }

  public function generateAccessTokenFromRefreshToken(array $user)
  {
    $payload = [
      "lat" => time(),
      "exp" => time() + (60 * 60),
      "id" => $user['user_id'],
      "firstname" => $user['first_name'],
      "lastname" => $user['last_name'],
      "email" => $user['email'],
      "phone" => $user['phone'],
      "role" => $user['role'],
      "type" => "access"
    ];

    return JWT::encode($payload, $this->secert, 'HS256');
  }
}

?>