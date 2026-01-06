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

  public function findBranchByOwnerId(string $ownerId): ?string
    {
        $sql = "SELECT branch_id FROM branches WHERE owner_id = ? LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("s", $ownerId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();

        return $row ? $row['branch_id'] : null;
    }

    

  public function crateBranch($branch_id, $owner_id, $name, $address, $phone)
  {

    $sql = "INSERT INTO branches (branch_id, owner_id, name, address, contact_phone)
        VALUES (?, ?, ?, ?, ?)";

    $stmt = $this->db->prepare($sql);
    $stmt->bind_param("sssss", $branch_id, $owner_id, $name, $address, $phone);


    $result = $stmt->execute();

    $stmt->close();

    return $result;
  }

  public function getAllBranches()
  {
    $sql = "SELECT * FROM branches";
    $result = $this->db->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
  }

}

?>