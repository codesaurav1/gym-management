<?php
namespace App\Middleware;

use Delight\Cookie\Cookie;
 
class setCookie
{
  public function setRefreshToken(string $refreshToken) 
  {
    $cookie = new Cookie('RefreshToken');
    $cookie->setValue($refreshToken);
    $cookie->setPath('/');
    $cookie->setHttpOnly(true);
    $cookie->setSecureOnly(false);
    $cookie->setExpiryTime(time() + (7 * 24 * 60 * 60));
    $cookie->setSameSiteRestriction('Strict');

    $cookie->saveAndSet();
  }
}

?>