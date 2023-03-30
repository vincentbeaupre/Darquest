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
    $joueurs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    Database::disconnect();

    return $joueurs;
  }

  public static function validerJoueur($alias, $motDePasse)
  {
    $joueurs = Database::getAllJoueurs();

    foreach ($joueurs as $joueur) {
      if ($joueur['alias'] == $alias && password_verify($motDePasse, $joueur['motDePasse'])) {

        $_SESSION['idJoueur'] = $joueur['idJoueur'];
        $_SESSION['alias'] = $joueur['alias'];
        $_SESSION['nom'] = $joueur['nom'];
        $_SESSION['prenom'] = $joueur['prenom'];
        $_SESSION['courriel'] = $joueur['courriel'];

        return true;
      }
    }
    return false;
  }

  public static function addJoueur($alias, $nom, $prenom, $motDePasse, $courriel)
  {
    $pdo = Database::connect();

    $sql = "INSERT INTO Joueurs (alias, nom, prenom, motDePasse, courriel)
            VALUES (:alias, :nom, :prenom, :motDePasse, :courriel)";
    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute(["alias" => $alias, "nom" => $nom, "prenom" => $prenom, "motDePasse" => $motDePasse, "courriel" => $courriel]);

    Database::disconnect();

    return $result;
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
  public static function getPanier($idJoueur)
  {
    $pdo = Database::connect();

    $sql = "SELECT * FROM Paniers inner join Items on Paniers.idItem = Items.idItem WHERE Paniers.idJoueur = :idJoueur";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([":idJoueur" => $idJoueur]);
    $results = $stmt->fetchAll();
    Database::disconnect();

    return $results;
  }
  public static function supprimerFromPanier($idItem, $idJoueur)
  {
    $pdo = Database::connect();

    $sql = "DELETE FROM Paniers WHERE idItem = :idItem AND idJoueur = :idJoueur";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([":idItem" => $idItem, ":idJoueur" => $idJoueur]);

    Database::disconnect();
  }
  public static function estQuantitéValide($idItem, $quantité)
  {
    $pdo = Database::connect();
    $sql = "SELECT quantiteStock FROM Items WHERE idItem = :idItem";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([":idItem" => $idItem]);
    $result = $stmt->fetchAll();

    if ($quantité <= $result[0]['quantiteStock']) {
      return true;
    } else {
      return false;
    }
  }
  public static function modifiéQuantitéItem($idJoueur, $idItem, $quantité)
  {
    if (Database::estQuantitéValide($idItem, $quantité)) {
      $pdo = Database::connect();
      $stmt = $pdo->prepare("CALL UpdatePanier(?,?,?)");
      $stmt->bindParam(1, $idJoueur, PDO::PARAM_INT);
      $stmt->bindParam(2, $idItem, PDO::PARAM_INT);
      $stmt->bindParam(3, $quantité, PDO::PARAM_INT);
      $stmt->execute();
    }
  }
  public static function payerPanier($idJoueur)
  {
    
  }

  public static function getSoldeJoueur($idJoueur)
  {
    $pdo = Database::connect();
    $sql = 'SELECT solde from Joueurs where idJoueur=?';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(1,$idJoueur);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row['solde'];
  }

  public static function getAllItems($prix, $type, $armes, $armures, $potions, $sorts)
  {
    $sql = "SELECT * FROM Items";
    if (isset($type) && $type == 'oui') {
      $stringWhere = "where typeItem =";
      if (isset($armes) && $armes == 'oui') {
        $stringWhere .= "'Armes' or typeItem =";
      }
      if (isset($armures) && $armures == 'oui') {
        $stringWhere .= "'Armures' or typeItem =";
      }
      if (isset($potions) && $potions == 'oui') {
        $stringWhere .= "'Sorts' or typeItem =";
      }
      if (isset($sorts) && $sorts == 'oui') {
        $stringWhere .= "'Potions' or typeItem =";
      }

      if (substr($stringWhere, -1) == "=") {
        $stringWhere = substr($stringWhere, 0, -14);
      }
      $sql = "SELECT * FROM Items " . $stringWhere;
    }
    if (isset($prix)) {
      if ($prix == 'asc') {
        $sql .= " ORDER BY typeItem,prix asc";
      }
      if ($prix == 'desc') {
        $sql .= " ORDER BY typeItem,prix desc";
      }
    }
    $pdo = Database::connect();
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

  /*
  public static function getAllItemsPrixAsc()
  {
    $pdo = Database::connect();
    $sql = "SELECT * FROM Items Order by prix asc";
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
  public static function getAllItemsPrixDesc()
  {
    $pdo = Database::connect();
    $sql = "SELECT * FROM Items Order by prix desc";
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
  */
}
