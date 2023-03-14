<?php

class Database
{
  private static $host = "167.114.152.54";
  public  static $databaseName = "dbdarquest6";
  private static $user = "equipe6";
  private static $password = "hx843s4s";
  private static $charset = "utf8";

  private static $options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
  ];

  private static $pdo = null;

  public static function connect()
  {
    try {
      self::$pdo = new PDO(
        "mysql:host=" . self::$host . ";dbname="
          . self::$databaseName . ";charset=" . self::$charset,
        self::$user,
        self::$password,
        self::$options
      );
    } catch (PDOException $e) {
      throw new Exception($e->getMessage(), $e->getCode());
    }

    return self::$pdo;
  }

  public static function disconnect()
  {
    self::$pdo = null;
  }
}
?>