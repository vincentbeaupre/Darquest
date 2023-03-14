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

  public static function getAllJoueurs()
  {
    $pdo = Database::connect();

    $stmt = $pdo->query("SELECT * FROM Joueurs");

    Database::disconnect();

    return $stmt;
  }

  public static function validerJoueur($alias, $password)
  {
    $stmt = Database::getAllJoueurs();

    foreach ($stmt as $joueur) {
      if ($joueur['pseudo'] == $alias && password_verify($password, $joueur['password'])) {

        $_SESSION['pseudo'] = $joueur['pseudo'];
        $_SESSION['nom'] = $joueur['nom'];
        $_SESSION['prenom'] = $joueur['prenom'];
        $_SESSION['email'] = $joueur['email'];

        return true;
      }

      return false;
    }
  }
}
