<?php
namespace App\utlis;

use Ramsey\Uuid\Uuid;

class UniqueId {
  public static function generateUuid()
  {
    $Uuid = Uuid::uuid4()->toString();
    return $Uuid;
  }
}



?>