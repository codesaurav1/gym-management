<?php
namespace App\model;

use App\Config\DB;

class BranchModel
{
  private $db;

  public function __construct()
  {
    $this->db = DB::getConnection();
  }

  public function crateBranch($branch_id, $user_id, $name, $address, $phone)
  {
    $sql = "INSERT INTO branches (branch_id, owner_id, name, address, contact_phone) 
            VALUES (?, ?, ?, ?, ?)";

    $stmt = $this->db->prepare($sql);

    $stmt->bind_param("sisss", $branch_id, $user_id, $name, $address, $phone);

    $sql = "INSERT INTO branches (branch_id, owner_id, name, address, contact_phone)
        VALUES (?, ?, ?, ?, ?)";

    $stmt = $this->db->prepare($sql);
    $stmt->bind_param("sssss", $branch_id, $user_id, $name, $address, $phone);


    $result = $stmt->execute();

    return $result;
  }

  public function getAllBranches() {
    $sql = "SELECT * FROM branches";
    $result = $this->db->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
  }

}

?>