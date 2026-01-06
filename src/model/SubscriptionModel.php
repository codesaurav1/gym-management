<?php
namespace App\model;

use App\Config\DB;

class SubscriptionModel
{
  protected $db;

  public function __construct()
  {
    $this->db = DB::getConnection();
  }

  public function CreateSubscription($subscriptionId, $planName, $price, $duration_days): bool
  {

    $sql = "INSERT INTO subscriptions (sub_id, plan_name, price, duration_days) VALUES (?,?,?,?)";

    $stmt = $this->db->prepare($sql);
    $stmt->bind_param("ssdi", $subscriptionId, $planName, $price, $duration_days);
    $result = $stmt->execute();
    return $result;
  }

  public function GetSubscription()
  {
    $sql = "SELECT * FROM subscriptions";
    $result = $this->db->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
  }

  public function UpdateSubscription(string $sub_id, array $data):mixed
  {
    $fields = array_map(fn($k) => "$k = ?", array_keys($data));
    $sql = "UPDATE subscriptions SET " . implode(', ', $fields) . " WHERE sub_id = ?";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([...array_values($data), $sub_id]);

    return $stmt->affected_rows;
  }

  public function DeleteSubscription($sub_id)
  {
    $sql = "DELETE FROM subscriptions WHERE sub_id = ?";
    $stmt = $this->db->prepare($sql);
    $stmt->bind_param("s", $sub_id);
    $result = $stmt->execute();
    return $result;
  }

  public function FindSubscriptionExist($sub_id)
  {
    $sql = "SELECT * FROM subscriptions WHERE sub_id = ?";
    $stmt = $this->db->prepare($sql);
    $stmt->bind_param("s", $sub_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
  }

  public function FindDublicateSubscription($planName)
  {
    $sql = "SELECT * FROM subscriptions WHERE plan_name = ?";
    $stmt = $this->db->prepare($sql);
    $stmt->bind_param("s", $planName);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
  }

}


?>