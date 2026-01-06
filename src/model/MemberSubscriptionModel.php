<?php 
namespace App\model;

use App\Config\DB;
class MemberSubscriptionModel
{
  protected $db;
  public function __construct()
  {
    $this->db = DB::getConnection();
  }

  public function FindSubscriptionExist( $member_id) {
    $sql = "SELECT * FROM member_subscriptions WHERE  member_id = ?";
    $stmt = $this->db->prepare($sql);
    $stmt->bind_param("s", $member_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
  }

  public function CreateMemberSubscription($MembershipSubsId, $MemberId, $SubscriptionId, $startDate, $endDate) {
    $sql = "INSERT INTO member_subscriptions (ms_id, member_id, sub_id, start_date, end_date) VALUES (?,?,?,?,?)";

    $stmt = $this->db->prepare($sql);
    $stmt->bind_param("sssss", $MembershipSubsId, $MemberId, $SubscriptionId, $startDate, $endDate);
    $result = $stmt->execute();
    return $result;
   }

   public function GetMemberSubscription($MemberId) {
    $sql = "SELECT * FROM member_subscriptions WHERE member_id = ?";
    $stmt = $this->db->prepare($sql);
    $stmt->bind_param("s", $MemberId);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
   }

   public function UpdateMemberSubscription($MemberId, $updateData) {

    $fields = array_map(fn($k) => "$k = ?", array_keys($updateData));

    $sql = "UPDATE member_subscriptions SET " . implode(', ', $fields) . " WHERE member_id = ?";
    $stmt = $this->db->prepare($sql);
    
    $stmt->execute([...array_values($updateData), $MemberId]);
    $result = $stmt->affected_rows;
    return $result;
   }

   public function DeleteMemberSubscription($MemberId) {
    $sql = "DELETE FROM member_subscriptions WHERE member_id = ?";
    $stmt = $this->db->prepare($sql);
    $stmt->bind_param("s", $MemberId);
    $result = $stmt->execute();
    return $result;
   }
}


?>