<?php
require_once __DIR__ . '/app/core/Router.php';

$router = new Router();

$router->get('', function () {
  echo 'Page d’accueil';
});

$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
