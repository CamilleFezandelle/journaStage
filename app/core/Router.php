<?php
class Router
{
  private array $getRoutes = [];
  private array $postRoutes = [];

  // GET
  public function get(string $path, $callback, ?callable $access = null): void
  {
    $this->getRoutes[$path] = [
      'callback' => $callback,
      'access' => $access
    ];
  }

  // POST
  public function post(string $path, $callback, ?callable $access = null): void
  {
    $this->postRoutes[$path] = [
      'callback' => $callback,
      'access' => $access
    ];
  }

  // DISPATCH
  public function dispatch(string $method, string $uri): void
  {
    $prefix = 'FEZANDELLECAMILLE/projects/JournaStage';
    $uri = trim(parse_url($uri, PHP_URL_PATH), '/');

    if (strpos($uri, $prefix) === 0) {
      $uri = substr($uri, strlen($prefix));
    }

    $routes = $method === 'POST' ? $this->postRoutes : $this->getRoutes;

    if (isset($routes[$uri])) {
      $route = $routes[$uri];

      if (isset($route['access']) && is_callable($route['access']) && !$route['access']()) {
        http_response_code(403);
        echo "403 Forbidden";
        return;
      }

      if (is_array($route['callback'])) {
        $controller = new $route['callback'][0]();
        call_user_func([$controller, $route['callback'][1]]);
      } else {
        call_user_func($route['callback']);
      }
    } else {
      http_response_code(404);
      echo "404 Not Found";
      return;
    }
  }
}
