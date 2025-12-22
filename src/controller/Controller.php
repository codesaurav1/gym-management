<?php
namespace App\controller;

class Controller
{
  public function sayHello()
  {
    header('Content-Type: application/json');
    echo json_encode([
      "success" => true,
      "message" => "Hello World!"
    ]);
  }



  public function getMembers()
  {
    $members = [
      ["id" => 1, "name" => "John Doe"],
      ["id" => 2, "name" => "Jane Smith"]
    ];
    header('Content-Type: application/json');
    echo json_encode($members);
  }
}
