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
    $conn = mysqli_connect(self::$host, self::$username, self::$password, self::$database);
    
    if (!$conn)
      die("Échec de connexion au serveur");
    else 
      mysqli_set_charset($conn, self::$charset);

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
      if ($joueur['alias'] == $alias && password_verify($password, $joueur['password'])) {

        $_SESSION['alias'] = $joueur['alias'];
        $_SESSION['nom'] = $joueur['nom'];
        $_SESSION['prenom'] = $joueur['prenom'];
        $_SESSION['courriel'] = $joueur['courriel'];
        $_SESSION['solde'] = $joueur['solde'];
        $_SESSION['estMage'] = $joueur['estMage'];
        $_SESSION['estAdmin'] = $joueur['estAdmin'];

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
    $stmt = $conn->prepare("CALL check_alias(?, @count)");
    $stmt->bind_param("s", $alias);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    mysqli_stmt_close($stmt);
    mysqli_close($conn);

    return $count;
}

}
