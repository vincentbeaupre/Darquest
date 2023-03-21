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
    $result = $conn->query("SELECT * FROM Joueurs");
    mysqli_close($conn);

    return $result;
  }


  public static function validerJoueur($alias, $motDePasse)
  {
    $joueurs = Database::getAllJoueurs();

    foreach ($joueurs as $joueur) {
      if ($joueur['alias'] == $alias && password_verify($motDePasse, $joueur['motDePasse'])) {

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

  public static function addJoueur($alias, $nom, $prenom, $motDePasse, $courriel)
  {
    $conn = Database::connect();
    $sql = "INSERT INTO Joueurs (alias, nom, prenom, password, email, token)
            VALUES (:pseudo, :nom, :prenom, :password, :email, :token)";
    $stmt = $conn->prepare($sql);
    $stmt->execute(["alias" => $alias, "nom" => $nom, "prenom" => $prenom, "motDePasse" => $motDePasse, "courriel" => $courriel]);
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
