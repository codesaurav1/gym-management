<?php
namespace App\Controller;

class DashBoardController {
  public function index() {

      

    header("Content-Type: application/json");
    echo json_encode([
      "success" => true,
      "message" => "Welcome to the dashboard",
      "data" => [
        "status" => [
          "member" => 120,
          "active seccion" => 15,
          "revenue" => 5000,
          "name" => "John Doe"
        ]
      ]
        ]);
  }
}

?>