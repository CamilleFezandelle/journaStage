<?php

class FlashCookie
{
  public static function set(string $name, string $value, int $duration = 10): void
  {
    setcookie($name, $value, [
      'expires' => time() + $duration,
      'path' => '/',
      'domain' => '',
      'secure' => false,
      // 'secure' => true, // HTTPS
      'httponly' => true,
      'samesite' => 'Strict'
    ]);
  }

  public static function getAndDelete(string $name): ?string
  {
    if (isset($_COOKIE[$name])) {
      $value = $_COOKIE[$name];
      setcookie($name, '', [
        'expires' => time() - 3600,
        'path' => '/',
        'domain' => '',
        'secure' => false,
        // 'secure' => true, // HTTPS
        'httponly' => true,
        'samesite' => 'Strict'
      ]);
      return $value;
    }
    return null;
  }
}
