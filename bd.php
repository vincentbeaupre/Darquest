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
        $_SESSION['estAdmin'] = $joueur['estAdmin'];
        $_SESSION['estMage'] = $joueur['estMage'];

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
      try {
        $stmt->execute();
      } catch (PDOException $e) {
        return false;
      }
      return true;
    }
  }

  public static function payerPanier($idJoueur)
  {
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
    $stmt = $pdo->prepare("CALL supprimerFromPanier(?,?)");
    $stmt->bindParam(1, $idJoueur, PDO::PARAM_INT);
    $stmt->bindParam(2, $idItem, PDO::PARAM_INT);
    try {
      $stmt->execute();
    } catch (PDOException $e) {
      return false;
    }
    return true;
  }
  public static function estQuantitéValide($idItem, $quantite)
  {
    $pdo = Database::connect();
    $sql = "SELECT quantiteStock FROM Items WHERE idItem = :idItem";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([":idItem" => $idItem]);
    $result = $stmt->fetchAll();
    if (count($result) > 0 && $quantite <= $result[0]['quantiteStock']) {
      return true;
    } else {
      return false;
    }
  }
  public static function modifiéQuantitéItem($idJoueur, $idItem, $quantite)
  {
    $pdo = Database::connect();
    $stmt = $pdo->prepare("CALL UpdatePanier(?,?,?)");
    $stmt->bindParam(1, $idJoueur, PDO::PARAM_INT);
    $stmt->bindParam(2, $idItem, PDO::PARAM_INT);
    $stmt->bindParam(3, $quantite, PDO::PARAM_INT);
    try {
      $stmt->execute();
    } catch (PDOException $e) {
      return false;
    }
    return true;
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
  public static function getItemDetails($idItem, $typeItem)
  {
    switch ($typeItem) {
      case 'Armes':
        $sql = 'SELECT i.idItem,nom,quantiteStock,prix,photo,typeItem,description,efficacite,genre
        FROM Items i JOIN Armes a ON i.idItem = a.idItem WHERE i.idItem=?';
        break;
      case 'Armures':
        $sql = 'SELECT i.idItem,nom,quantiteStock,prix,photo,typeItem,taille,matiere
        FROM Items i JOIN Armures a ON i.idItem = a.idItem WHERE i.idItem=?';
        break;
      case 'Sorts':
        $sql = 'SELECT i.idItem,nom,quantiteStock,prix,photo,typeItem,instantane,nbpointvie
        FROM Items i JOIN Sorts s ON i.idItem = s.idItem WHERE i.idItem=?';
        break;
      case 'Potions':
        $sql = 'SELECT i.idItem,nom,quantiteStock,prix,photo,typeItem,duree,effet
        FROM Items i JOIN Potions p ON i.idItem = p.idItem WHERE i.idItem=?';
        break;
    }

    $pdo = Database::connect();
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(1, $idItem);
    $stmt->execute();
    $results = $stmt->fetch(PDO::FETCH_BOTH);
    Database::disconnect();
    return $results;
  }


  //Admin
 public static function ajouterQuestion($enonce,$difficulty,$reponse1,$reponse2,$reponse3,$reponse4,$bonneReponse)
 {

  $pdo = Database::connect();
  $stmt = $pdo->prepare("CALL ajouterQuestion(?,?,?,?,?,?)");
  $stmt->bindParam(1, $enonce, PDO::PARAM_STR);
  $stmt->bindParam(2, $difficulty, PDO::PARAM_STR_CHAR);

  if($bonneReponse == 1)
  {
    $stmt->bindParam(3, $reponse2, PDO::PARAM_STR);
    $stmt->bindParam(4, $reponse3, PDO::PARAM_STR);
    $stmt->bindParam(5, $reponse4, PDO::PARAM_STR);
    $stmt->bindParam(6, $reponse1, PDO::PARAM_STR);
  }
  else if($bonneReponse == 2)
  {
    $stmt->bindParam(3, $reponse1, PDO::PARAM_STR);
    $stmt->bindParam(4, $reponse3, PDO::PARAM_STR);
    $stmt->bindParam(5, $reponse4, PDO::PARAM_STR);
    $stmt->bindParam(6, $reponse2, PDO::PARAM_STR);
  }
  else if($bonneReponse == 3)
  {
    $stmt->bindParam(3, $reponse1, PDO::PARAM_STR);
    $stmt->bindParam(4, $reponse2, PDO::PARAM_STR);
    $stmt->bindParam(5, $reponse4, PDO::PARAM_STR);
    $stmt->bindParam(6, $reponse3, PDO::PARAM_STR);
  }
  else if($bonneReponse == 4)
  {
    $stmt->bindParam(3, $reponse1, PDO::PARAM_STR);
    $stmt->bindParam(4, $reponse2, PDO::PARAM_STR);
    $stmt->bindParam(5, $reponse3, PDO::PARAM_STR);
    $stmt->bindParam(6, $reponse4, PDO::PARAM_STR);
  }
  
  try{
    $stmt->execute();
  }catch (PDOException $e){
    return false;
  }
  return true;
  
 }

















  //Inventaire:
  public static function getInventaire($idJoueur)
  {
    $pdo = Database::connect();

    $sql = "SELECT * FROM Inventaires v JOIN Items i ON v.idItem = i.idItem WHERE idJoueur = :idJoueur";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([":idJoueur" => $idJoueur]);
    $results = $stmt->fetchAll();
    Database::disconnect();

    return $results;
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
    $results = $stmt->fetchAll();
    Database::disconnect();
    return $results;
  }

  //------------------- Enigma

  public static function getReponses($idQuestion)
  {
    $pdo = Database::connect();
    $sql = "SELECT * 
    FROM Reponses 
    WHERE idQuestion = :idQuestion
    ORDER BY RAND()";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([":idQuestion" => $idQuestion]);
    $result = $stmt->fetchAll();
    Database::disconnect();

    return $result;
  }

  public static function getQuestionAleatoire($idJoueur) {
    $pdo = Database::connect();
    $sql = "SELECT *
    FROM Questions
    WHERE idQuestion NOT IN (
      SELECT idQuestion
      FROM Joueurs_Questions
      WHERE idJoueur = :idJoueur
    )
    ORDER BY RAND()
    LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([":idJoueur" => $idJoueur]);
    $result = $stmt->fetch();
    Database::disconnect();

    return $result;
  }
}
