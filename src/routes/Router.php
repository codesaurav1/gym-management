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

  public function put($path, $action)
  {
    $this->routes['PUT'][$path] = $action;
  }

  public function delete($path, $action)
  {
    $this->routes['DELETE'][$path] = $action;
  }

  public function dispatch($method, $uri)
{
    $sanatizedUri = filter_var($uri, FILTER_SANITIZE_URL);
    $path = parse_url($sanatizedUri, PHP_URL_PATH);

    if (!isset($this->routes[$method])) {
        $this->notFound();
        return;
    }

    foreach ($this->routes[$method] as $route => $action) {

        // Convert /api/user/:id → regex
        $pattern = preg_replace('#:([\w]+)#', '([^/]+)', $route);
        $pattern = "#^" . $pattern . "$#";

        if (preg_match($pattern, $path, $matches)) {

            array_shift($matches); // remove full match

            // Extract param names
            preg_match_all('#:([\w]+)#', $route, $paramNames);

            $params = [];
            foreach ($paramNames[1] as $index => $name) {
                $params[$name] = $matches[$index];
            }

            // ✅ Closure route
            if (is_callable($action)) {
                call_user_func($action, $params);
                return;
            }

            // ✅ Controller@method route
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

                $obj->$methodName($params);
                return;
            }
        }
    }

    $this->notFound();
}


  // public function dispatch($method, $uri)
  // {
  //   $sanatizedUri = filter_var($uri, FILTER_SANITIZE_URL);
  //   $path = parse_url($sanatizedUri, PHP_URL_PATH);

  //   if (!isset($this->routes[$method][$path])) {
  //     $this->notFound();
  //     return;
  //   }

  //   $action = $this->routes[$method][$path];

  //   // ✅ If route action is a Closure
  //   if (is_callable($action)) {
  //     call_user_func($action);
  //     return;
  //   }

  //   // ✅ If route action is Controller@method
  //   if (is_string($action)) {
  //     [$controller, $methodName] = explode('@', $action);

  //     $controllerClass = "App\\controller\\$controller";

  //     if (!class_exists($controllerClass)) {
  //       throw new \Exception("Controller $controllerClass not found");
  //     }

  //     $obj = new $controllerClass();

  //     if (!method_exists($obj, $methodName)) {
  //       throw new \Exception("Method $methodName not found");
  //     }

  //     $obj->$methodName();
  //     return;
  //   }

  //   $this->notFound();
  // }

  private function notFound()
  {
    header('Content-Type: application/json');
    http_response_code(404);
    echo json_encode(["error" => "Route not found"]);
  }

}
