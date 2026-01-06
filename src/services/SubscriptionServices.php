<?php
namespace App\services;

use App\model\SubscriptionModel;
use App\middleware\TokenAccesstractor;
use App\model\AuthModel;
use App\utlis\UniqueId;
use ReturnTypeWillChange;

class SubscriptionServices
{

  private SubscriptionModel $subscriptionModel;
  private AuthModel $authModel;

  private TokenAccesstractor $tokenAccesstractor;

  protected $uniqueId;

  public function __construct()
  {
    $this->subscriptionModel = new SubscriptionModel();
    $this->authModel = new AuthModel();
    $this->uniqueId = UniqueId::generateUuid();
  }

  public function CreateSubscription($data)
  {
    $subscriptionId = $this->uniqueId;
    $planName = $data['plan_name'];
    $price = $data['price'];
    $duration_days = $data['duration_day'];


    $checkSub = $this->subscriptionModel->FindDublicateSubscription($planName);

    // Check if subscription already exist
    if ($checkSub !== null) {
      return [
        'success' => false,
        'message' => 'Subscription already exist with this name',
        'data' => $checkSub
      ];
    }


    $result = $this->subscriptionModel->CreateSubscription($subscriptionId, $planName, $price, $duration_days);

    return $result;
  }

  public function GetSubscription()
  {
    $result = $this->subscriptionModel->GetSubscription();

    return $result;
  }

  public function UpdateSubcription($sub_id, $data)
  {
    $allowed = ['plan_name', 'duration_day', 'price'];

    $updateData = array_intersect_key($data, array_flip($allowed));

    if (empty($updateData)) {
      return ['success' => false, 'error' => 'No valid field to update'];
    }
    return $this->subscriptionModel->UpdateSubscription($sub_id, $updateData);

  }

  public function DeleteSubscription($sub_id)
  {
    // Check if subscription already delted
    $subDel = $this->subscriptionModel->FindSubscriptionExist($sub_id);


    if (!$subDel)
      return ['success' => false, 'message' => 'Subscription already deleted'];

    $result = $this->subscriptionModel->DeleteSubscription($sub_id);

    return ['success' => true, 'message' => $result];
  }
}

?>