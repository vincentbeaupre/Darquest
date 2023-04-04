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

  //------------------- Joueur

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
  public static function ajouterItemPanier($idItem, $quantite, $idJoueur)
  {
    if (Database::estQuantitéValide($idItem, $quantite)) {
      $pdo = Database::connect();
      $stmt = $pdo->prepare("CALL AjoutPanier(?,?,?)");
      $stmt->bindParam(1, $idItem, PDO::PARAM_INT);
      $stmt->bindParam(2, $quantite, PDO::PARAM_INT);
      $stmt->bindParam(3, $idJoueur, PDO::PARAM_INT);
      try{
        $stmt->execute();
      }catch (PDOException $e){
        return false;
      }
      return true;
    }
  }

  public static function payerPanier($idJoueur) {
    $pdo = Database::connect();
    $stmt = $pdo->prepare("CALL PayerPanier(?)");
    $stmt->bindParam(1, $idJoueur, PDO::PARAM_INT);

    return $stmt->execute();
  }


  public static function getNumItemsInCart($idJoueur)
  {
    $pdo = Database::connect();

    $sql = "SELECT SUM(quantiteAchat) FROM Paniers WHERE idJoueur = :idJoueur";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([":idJoueur" => $idJoueur]);
    $result = $stmt->fetchColumn();
    Database::disconnect();

    return $result;
  }

  public static function supprimerFromPanier($idItem, $idJoueur)
  {
    $pdo = Database::connect();

    $sql = "DELETE FROM Paniers WHERE idItem = :idItem AND idJoueur = :idJoueur";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([":idItem" => $idItem, ":idJoueur" => $idJoueur]);
  }
  public static function estQuantitéValide($idItem, $quantite)
  {
    $pdo = Database::connect();
    $sql = "SELECT quantiteStock FROM Items WHERE idItem = :idItem";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([":idItem" => $idItem]);
    $result = $stmt->fetchAll();
    if ($quantite <= $result[0]['quantiteStock']) {
      return true;
    } else {
      return false;
    }
  }
  public static function modifiéQuantitéItem($idJoueur, $idItem, $quantite)
  {
    if (Database::estQuantitéValide($idItem, $quantite)) {
      $pdo = Database::connect();
      $stmt = $pdo->prepare("CALL UpdatePanier(?,?,?)");
      $stmt->bindParam(1, $idJoueur, PDO::PARAM_INT);
      $stmt->bindParam(2, $idItem, PDO::PARAM_INT);
      $stmt->bindParam(3, $quantité, PDO::PARAM_INT);
      try{
        $stmt->execute();
      }catch (PDOException $e){
        return false;
      }
      return true;
    }
  }

  public static function getSoldeJoueur($idJoueur)
  {
    $pdo = Database::connect();
    $sql = 'SELECT solde FROM Joueurs WHERE idJoueur = :idJoueur';
    $stmt = $pdo->prepare($sql);
    $stmt->execute([":idJoueur" => $idJoueur]);
    $result = $stmt->fetchColumn();

    Database::disconnect();

    return $result;
  }

  //------------------- Items

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
        $stringWhere .= "'Potions' or typeItem =";
      }
      if (isset($sorts) && $sorts == 'oui') {
        $stringWhere .= "'Sorts' or typeItem =";
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
      $montant = afficherMontant($row['prix']);
      echo "<div id='".$row['idItem']."'>
      <a class='itemCardChild' href='http://167.114.152.54/~darquest6/itemDetails.php?idItem=" . $row['idItem'] . "&typeItem=" . $row['typeItem'] . "'>
      <h4 style='font-weight:bold;margin:5px;''>" . $row['nom'] . "</h4>
      <img src=" . $row['photo'] . " style='border:3px black solid;border-radius:10px;'>
      <span>Stock: <span>" . $row['quantiteStock'] . "</span></span>
      <span>Prix: " . $montant . "</span>
      <input type='hidden' id='idItem' name='idItem' value=" . $row['idItem'] . " />
      <input type='hidden' id='typeItem' name='typeItem' value=" . $row['typeItem'] . " />
      </a>
    </div>
    ";
      //<a class='itemCardLink' href='javascript:void(0);' onclick='displayDetails(".$row['idItem'].",`".$row['typeItem']."`)'>
    }
    Database::disconnect();
  }

  public static function getItemDetails($idItem, $typeItem)
  {
    switch ($typeItem) {
      case 'Armes':
        $sql = 'SELECT i.idItem,nom,quantiteStock,prix,photo,typeItem,description,efficacite,genre
        FROM Items i JOIN Armes a ON i.idItem = a.idItem WHERE i.idItem=?';
        $nomColonnes = ['Description: ', 'Efficacité: '];
        break;
      case 'Armures':
        $sql = 'SELECT i.idItem,nom,quantiteStock,prix,photo,typeItem,taille,matiere
        FROM Items i JOIN Armures a ON i.idItem = a.idItem WHERE i.idItem=?';
        $nomColonnes = ['Taille: ', 'Matière: '];
        break;
      case 'Sorts':
        $sql = 'SELECT i.idItem,nom,quantiteStock,prix,photo,typeItem,instantane,nbpointvie
        FROM Items i JOIN Sorts s ON i.idItem = s.idItem WHERE i.idItem=?';
        $nomColonnes = ['Instantanéité: ', 'Nombre de point de vie: '];
        //Oui oui, Instantanéité est un vrai mot: https://www.larousse.fr/dictionnaires/francais/instantan%C3%A9it%C3%A9/43422
        break;
      case 'Potions':
        $sql = 'SELECT i.idItem,nom,quantiteStock,prix,photo,typeItem,duree,effet
        FROM Items i JOIN Potions p ON i.idItem = p.idItem WHERE i.idItem=?';
        $nomColonnes = ['Durée: ', 'Effet: '];
        break;
    }

    $pdo = Database::connect();
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(1, $idItem);
    $stmt->execute();
    while ($row = $stmt->fetch(PDO::FETCH_BOTH)) {
      echo "<span>Type: " . $row['typeItem'] . "</span>
      <img src='" . $row['photo'] . "' width='128' height='128' style='border:3px black solid;border-radius:10px;'>
      <h1>" . $row['nom'] . "</h1>
      <span>Stock: " . $row['quantiteStock'] . "</span>
      <span>" . $nomColonnes[0] . $row[6] . "</span>
      <span>" . $nomColonnes[1] . $row[7] . "</span>";
      if ($typeItem == 'Armes') {
        echo "<span>Genre: " . $row['genre'] . "</span>";
      }
      echo "<span>Prix: ";
      echo afficherMontant($row['prix']) . "</span>";
      echo "<span>
      <a style='text-decoration: none; color: #ffffff' href='market.php'>
      <i class='fa fa-arrow-left fa-2x' style='padding:10px;'></i>
      </a>
      </span>
      <span>
      Ajouter l'item au panier:
      </span>";
      echo "<span>
      <form method='POST' action='market.php'>
        <input type='hidden' name='idItem' value='" . $row['idItem'] . "'>
        <label for='quantite'>Quantité (entre 1 and " . $row['quantiteStock'] . "):</label>
          <input type='number' id='quantite' name='quantite' min='1' max=" . $row['quantiteStock'] . ">
        <label for='btnSubmit'></label>
        <button id='btnSubmit' type='submit'>
          <i class='fa fa-plus'></i>
        </button>
      </form>
      </span>";
    }
    Database::disconnect();
  }
  //Inventaire:
  public static function getInventaire($idJoueur){
    $pdo = Database::connect();

    $sql = "SELECT * FROM Inventaires v JOIN Items i ON v.idItem = i.idItem WHERE idJoueur = :idJoueur";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([":idJoueur" => $idJoueur]);
    $results = $stmt->fetchAll();
    Database::disconnect();

    return $results;
  }

/*
<a style='text-decoration: none; color: #ffffff' href='market.php'>
<i class='fa fa-cart-arrow-down fa-2x' style='padding:10px;'></i>
</a>
*/
}
