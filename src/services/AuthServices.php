<?php
namespace App\services;

use App\security\JWTServices;
use App\model\AuthModel;
use Ramsey\Uuid\Uuid;

class AuthServices
{
  private AuthModel $model;
  private JWTServices $jwt;

  public function __construct()
  {
    $this->model = new AuthModel();
    $this->jwt = new JWTServices();
  }

  public function signup(array $data)
  {
    $uuid = Uuid::uuid4()->toString();

    $this->model->createUser(
      $uuid,
      $data['firstname'],
      $data['lastname'],
      $data['email'],
      $data['phone'] ?? null,
      $data['password'],
      $data['role'] ?? 'member'
    );

    return ["message" => "User registred successfully"];
  }

  public function login(array $data)
  {
    $user = $this->model->findUser($data['email']);

    if (!$user || !password_verify($data['password'], $user['password_hash'])) {
      throw new \Exception("Invalid credentials", 401);
    }

    $tokens = $this->jwt->generateTokens($user);

    // Store refresh token
    $TokenId = Uuid::uuid4()->toString();
    $userId = $user['user_id'];
    $refreshToken = $tokens['refresh_token'];
    $refreshTokenHash = hash('sha256', $refreshToken);


    try {
      $this->model->storeRefreshToken($TokenId, $userId, $refreshTokenHash);
      return $tokens;
    } catch (\Exception $e) {
      error_log("Refresh token storage error: " . $e->getMessage());
      throw new \Exception("Failed to store refresh token", 500);
    }

  }
  public function generateAccessToken(array $data)
  {
    if (!isset($data['refresh_token'])) {
      throw new \Exception("Refresh token is required", 402);
    }

    $refreshToken = $data['refresh_token'];

    // Verify refresh token JWT
    $payload = $this->jwt->verifyRefreshToken($refreshToken);
    $userId = $payload->sub;

    // Verify refresh token exist in DB
    $refreshTokenHash = hash('sha256', $refreshToken);
    $refreshTokenExists = $this->model->verifyRefreshTokenDB($userId, $refreshTokenHash);
    if (!$refreshTokenExists) {
      throw new \Exception("Invalid refresh token", 401);
    }

    // Get User
    $user = $this->model->findUserById($userId);

    if (!$user) {
      throw new \Exception("User not found", 404);
    }

    // Generate new access token ONLY
    $newAccessToken = $this->jwt->generateAccessTokenFromRefreshToken($user);

    return [
      "access_token" => $newAccessToken
    ];

  }
}


?>