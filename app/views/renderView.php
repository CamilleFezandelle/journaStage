<?php

function renderView(string $view, array $data = []): void
{
  extract($data);

  $scripts = $data['scripts'] ?? [];

  ob_start();
  include __DIR__ . '/pages/' . $view . '.php';
  $content = ob_get_clean();

  include __DIR__ . '/base.php';
}
