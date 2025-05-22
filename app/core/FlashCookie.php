<?php

class FlashCookie
{
  private bool $secure;

  public function __construct()
  {
    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' && $_SERVER['SERVER_PORT'] == 443) {
      $this->secure = true;
    } else {
      $this->secure = false;
    }
  }

  public function set(string $name, string $value, int $duration = 10): void
  {
    setcookie($name, $value, [
      'expires' => time() + $duration,
      'path' => '/',
      'domain' => '',
      'secure' => $this->secure,
      'httponly' => true,
      'samesite' => 'Strict'
    ]);
  }

  public function getAndDelete(string $name): ?string
  {
    if (isset($_COOKIE[$name])) {
      $value = $_COOKIE[$name];
      setcookie($name, '', [
        'expires' => time() - 3600,
        'path' => '/',
        'domain' => '',
        'secure' => $this->secure,
        'httponly' => true,
        'samesite' => 'Strict'
      ]);
      return $value;
    }
    return null;
  }
}
