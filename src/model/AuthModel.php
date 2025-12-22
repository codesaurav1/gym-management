<?php
namespace App\model;

use App\Config\DB;

class AuthModel
{
  protected $db;

  public function __construct()
  {
    $this->db = DB::getConnection();
  }

  // Create user 
  public function createUser($uuid, $firstname, $lastname, $email, $phone, $password, $role = 'member')
  {
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    $stmt = $this->db->prepare("INSERT INTO users (user_id, first_name, last_name, email, phone, password_hash, role) VALUES (?,?,?,?,?,?,?)");
    $stmt->bind_param("sssssss", $uuid, $firstname, $lastname, $email, $phone, $hashedPassword, $role);
    return $stmt->execute();
  }

  // Find the user by username
  public function findUser($email)
  {
    $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
  }

  public function storeRefreshToken($tokenId,$user_id, $refresh_token)
  {
    $stmt = $this->db->prepare("INSERT INTO refresh_tokens (token_id,user_id, refresh_token) VALUES (?,?,?)");
    $stmt->bind_param("sss",$tokenId, $user_id, $refresh_token);
    $result = $stmt->execute();
    return $result;
  }

  public function verifyRefreshTokenDB($userId, $refreshHashToken) {
    $stmt = $this->db->prepare("SELECT * FROM refresh_tokens WHERE user_id = ? AND refresh_token
    = ? LIMIT 1");

    $stmt->bind_param("ss", $userId, $refreshHashToken);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
  }

  public function findUserById(string $userId) {
    $stmt = $this->db->prepare("SELECT * FROM users WHERE user_id = ? LIMIT 1");
    $stmt->bind_param("s", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
  }

}

?>