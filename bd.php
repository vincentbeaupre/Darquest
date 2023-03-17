<?php

class Database
{
  private static $host = "167.114.152.54";
  private static $database = "dbdarquest6";
  private static $username = "equipe6";
  private static $password = "hx843s4s";
  private static $charset = "utf8";

  public static function connect()
  {
    $conn = mysqli_connect(self::$host, self::$username, self::$password, self::$database, null, null, self::$charset);
    
    if (!$conn)
      die("Échec de connexion au serveur: " . mysqli_connect_error());

    return $conn;
  }

  public static function getAllJoueurs()
  {
    $conn = Database::connect();
    $result = $conn->query("CALL GetAllJoueurs()");
    $rows = $result->fetch_all();
    mysqli_close($conn);
  
    return $rows;
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

  public static function addJoueur($alias, $nom, $prenom, $password, $email)
  {
    $conn = Database::connect();
    $stmt = $conn->prepare("CALL AjouterJoueur(?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $alias, $nom, $prenom, $password, $email);
    $stmt->execute();
    mysqli_close($conn);
  }

  public static function checkAlias($alias)
  {
    $conn = Database::connect();

    $sql = "SELECT COUNT(alias) FROM Joueurs WHERE alias = '$alias' LIMIT 1";
    $result = $conn->query($sql);
    $count = $result->fetch_column();
    mysqli_close($conn);

    return $count;
  }
}
