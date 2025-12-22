<?php 
namespace App\Config;

use mysqli;

class DB {
  private static $instance = null;

  public static function getConnection() {
    if(self::$instance === null) {
      self::$instance = new mysqli(
        $_ENV['DB_HOST'],
        $_ENV['DB_USER'],
        $_ENV['DB_PASS'],
        $_ENV['DB_NAME']
      );
      if(self::$instance->connect_error) {
        die("Connection failed: " . self::$instance->connect_error);
      }
    }
    return self::$instance;
  }
}

?>