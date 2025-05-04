<?php

class Router
{
  private array $getRoutes = [];
  private array $postRoutes = [];

  // GET
  public function get(string $path, callable $callback): void
  {
    $this->getRoutes[$path] = $callback;
  }

  // POST
  public function post(string $path, callable $callback): void
  {
    $this->postRoutes[$path] = $callback;
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
      $routes[$uri]();
    } else {
      http_response_code(404);
      echo "404 Not Found";
    }
  }
}
