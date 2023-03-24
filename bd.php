<?php

class Database
{
  private static $host = "167.114.152.54";
  private static $database = "dbdarquest6";
  private static $username = "equipe6";
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
          . self::$database . ";charset=" . self::$charset,
        self::$username,
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


  public static function validerJoueur($alias, $motDePasse)
  {
    $joueurs = Database::getAllJoueurs();

    foreach ($joueurs as $joueur) {
      if ($joueur['alias'] == $alias && password_verify($motDePasse, $joueur['motDePasse'])) {

        $_SESSION['pseudo'] = $joueur['pseudo'];
        $_SESSION['nom'] = $joueur['nom'];
        $_SESSION['prenom'] = $joueur['prenom'];
        $_SESSION['email'] = $joueur['email'];

        return true;
      }

      return false;
    }
  }

  public static function addJoueur($alias, $nom, $prenom, $motDePasse, $courriel)
  {
    $pdo = Database::connect();

    $sql = "INSERT INTO Joueurs (alias, nom, prenom, motDePasse, courriel)
            VALUES (:alias, :nom, :prenom, :motDePasse, :courriel)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(["alias" => $alias, "nom" => $nom, "prenom" => $prenom, "motDePasse" => $motDePasse, "courriel" => $courriel]);

    Database::disconnect();
  }

  public static function checkAlias($alias)
  {
    $pdo = Database::connect();

    $sql = "SELECT COUNT(alias) FROM Joueurs WHERE alias = '$alias' LIMIT 1";
    $result = $pdo->query($sql);
    $count = $result->fetchColumn();
    Database::disconnect();

    return $count;
  }

  //------------------- Panier
  public static function getPanier($idJoueur) // a Débuger plus tard avec id Joueur et panier rempli
  {
    $pdo = Database::connect();

    $sql = "SELECT * FROM Paniers inner join Items on Paniers.idItem = Item.idItem WHERE Paniers.idJoueur = $idJoueur";
    $result = $pdo->query($sql);
    Database::disconnect();

    return $result;
  }
  public static function supprimerFromPanier()
  {
    $pdo = Database::connect();
  }
  public static function estQuantitéValide($idItem, $quantité)
  {
    return true;
  }
  public static function modifiéQuantitéItem($idJoueur, $idItem, $quantité)
  {
    if (Database::estQuantitéValide($idItem, $quantité)) {
    }
  }
  public static function payerPanier($idJoueur)
  {
  }
  public static function getSoldeJoueur($idJoueur)
  {
    
  }
  public static function getAllItemsMinimum()
  {
    $pdo = Database::connect();
    $sql = "SELECT * FROM Items";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      echo "<div class='itemCardChild'>
      <h4 style='font-weight:bold;margin:5px;''>" . $row['nom'] . "</h4>
      <img src=" . $row['photo'] . " style='border:3px black solid;border-radius:10px;'>
      <span>Stock: <span>" . $row['quantiteStock'] . "</span></span>
      <span>Prix: <span>" . $row['prix'] . "</span></span>
  </div>
";
    }
    Database::disconnect();
  }
}
