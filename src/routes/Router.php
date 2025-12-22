<?php
namespace App\routes;

class Router
{
  private $routes = [];

  public function get($path, $action)
  {
    $this->routes['GET'][$path] = $action;
  }

  public function post($path, $action)
  {
    $this->routes['POST'][$path] = $action;
  }

  public function dispatch($method, $uri)
  {
    $sanatizedUri = filter_var($uri, FILTER_SANITIZE_URL);
    $path = parse_url($sanatizedUri, PHP_URL_PATH);

    if (!isset($this->routes[$method][$path])) {
      $this->notFound();
      return;
    }

    $action = $this->routes[$method][$path];

    // ✅ If route action is a Closure
    if (is_callable($action)) {
      call_user_func($action);
      return;
    }

    // ✅ If route action is Controller@method
    if (is_string($action)) {
      [$controller, $methodName] = explode('@', $action);

      $controllerClass = "App\\controller\\$controller";

      if (!class_exists($controllerClass)) {
        throw new \Exception("Controller $controllerClass not found");
      }

      $obj = new $controllerClass();

      if (!method_exists($obj, $methodName)) {
        throw new \Exception("Method $methodName not found");
      }

      $obj->$methodName();
      return;
    }

    $this->notFound();
  }

  private function notFound()
  {
    header('Content-Type: application/json');
    http_response_code(404);
    echo json_encode(["error" => "Route not found"]);
  }

}
