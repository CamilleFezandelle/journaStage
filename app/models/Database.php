<?php

class Database
{
  private static ?PDO $instance = null;

  private function __construct() {}
  private function __clone() {}

  public static function getInstance(): PDO
  {
    if (self::$instance === null) {
      $config = require __DIR__ . '/../../../../config/database.php';
      self::$instance = new PDO(
        $config['DB_DSN'],
        $config['DB_USER'],
        $config['DB_PASS'],
        [
          PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
          PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
      );
    }
    return self::$instance;
  }

  public static function prepare(string $query): PDOStatement
  {
    return self::getInstance()->prepare($query);
  }
}
