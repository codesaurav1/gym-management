<?php
namespace App\services;

use App\Model\MemberSubscriptionModel;
use App\middleware\TokenAccesstractor;
use App\utlis\UniqueId;
use BcMath\Number;

class MemberSubscriptionServices
{
  private MemberSubscriptionModel $memberSubscriptionModel;

  private TokenAccesstractor $tokenAccesstractor;

  protected $uuid;

  public function __construct()
  {
    $this->memberSubscriptionModel = new MemberSubscriptionModel();
    $this->tokenAccesstractor = new TokenAccesstractor();
    $this->uuid = UniqueId::generateUuid();
  }

  public function CreateMemberSubscription(string $data)
  {
    $MembershipSubsId = $this->uuid;
    $MemberId = $this->tokenAccesstractor->findUserId();
    $SubscriptionId = $data;
    $startDate = date('Y-m-d H:i:s');
    $endDate = date('Y-m-d H:i:s', strtotime($startDate . ' +1 month'));

    $checkSubExist = $this->memberSubscriptionModel->FindSubscriptionExist($MemberId);

    if ($checkSubExist) {
      return ["success" => false, "message" => "Subscription already exist", "data" => $checkSubExist];
    }


    $result = $this->memberSubscriptionModel->CreateMemberSubscription($MembershipSubsId, $MemberId, $SubscriptionId, $startDate, $endDate);

    return ["success" => true, "message" => "Member Subscription created successfully"];
  }

  public function GetMemberSubscription()
  {
    $MemberId = $this->tokenAccesstractor->findUserId();
    $result = $this->memberSubscriptionModel->GetMemberSubscription($MemberId);

    if (!$result) {
      return ["success" => false, "message" => "Member Subscription not found"];
    }
    return $result;
  }

  public function UpdateMemberSubscription($data)
  {
    $MemberId = $this->tokenAccesstractor->findUserId();
    $allowed = ['start_date', 'end_date'];

    $updateData = array_intersect_key($data, array_flip($allowed));

    if (empty($updateData)) {
      return ["success" => false, "message" => "No valid field to update"];
    }

    // Update the member subscription with the filtered data
    $result = $this->memberSubscriptionModel->UpdateMemberSubscription($MemberId, $updateData);

    if(!$result) {
      return ["success" => false, "message" => "Member Subscription not found"];
    }

    return ["success" => true, "message" => "Member Subscription updated successfully"];
  }

  public function DeleteMemberSubscription($MemberId)
  {
    $result = $this->memberSubscriptionModel->DeleteMemberSubscription($MemberId);
    if(!$result) {
      return ["success" => false, "message" => "Member Subscription not found"];
    }
    return ["success" => true, "message" => "Member Subscription deleted successfully"];
  }
}


?>