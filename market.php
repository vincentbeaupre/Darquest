<?php
include 'bd.php';
require_once 'fonctions.php';
session_start();

(isset($_SESSION['idJoueur'])) ? $idJoueur = $_SESSION['idJoueur'] : "";
$prix = 'asc';
$type = 'non';
$armes = 'non';
$armures = 'non';
$potions = 'non';
$sorts = 'non';
if ($_SERVER['REQUEST_METHOD'] === "GET") {
  if (isset($_GET['trierPrix'])) {
    $prix = $_GET['trierPrix'];
  }
  if (isset($_GET['Arme']) || isset($_GET['Armure']) || isset($_GET['Potion']) || isset($_GET['Sort'])) {
    $type = 'oui';
    if (isset($_GET['Arme'])) {
      $armes = 'oui';
    }
    if (isset($_GET['Armure'])) {
      $armures = 'oui';
    }
    if (isset($_GET['Potion'])) {
      $potions = 'oui';
    }
    if (isset($_GET['Sort'])) {
      $sorts = 'oui';
    }
  }
}
?>

<!DOCTYPE html>
<html lang="fr">

<?php include "header.php" ?>

<main>
  <div id="marketPage">
    <div id="box1">
      <div id="marketItemList" class="itemCardMain">
        <?php
        Database::getAllItems($prix, $type, $armes, $armures, $potions, $sorts);
        ?>
      </div>
      <div id="marketSearchBy">
        <h2>Recherche</h2>
        <form action="" method="GET">
          <h3>Filtrer par type</h3>
          <input type="checkbox" id="Armure" name="Armure" value="Armure" <?php echo (isset($_GET['Armure']))? 'checked':''?>>
          <label for="Armure"> Armures</label><br>
          <input type="checkbox" id="Armes" name="Arme" value="Arme" <?php echo (isset($_GET['Arme']))? 'checked':''?>>
          <label for="Armes"> Armes</label><br>
          <input type="checkbox" id="Potion" name="Potion" value="Potion" <?php echo (isset($_GET['Potion']))? 'checked':''?>>
          <label for="Potion"> Potion</label><br>
          <input type="checkbox" id="Sort" name="Sort" value="Sort" <?php echo (isset($_GET['Sort']))? 'checked':''?>>
          <label for="Sort"> Sort</label><br>
          <h3>Trier par</h3>
          <label for="trierPrix">Prix:&nbsp&nbsp</label>
          <input type="radio" id="prixASC" name="trierPrix" value="asc"><i class="fa fa-arrow-up" aria-hidden="true"></i>
          <input type="radio" id="prixDESC" name="trierPrix" value="desc"><i class="fa fa-arrow-down" aria-hidden="true"></i>
          <br>
          <input class="button" type="submit" name="filtrage_btn" value="Enter">
        </form>
        <a link href='http://167.114.152.54/~darquest6/market.php'>Réinitialiser</a>
      </div>
    </div>
    <div id="box2">
      <div id="currency">
        <?php
        echo (isset($_SESSION['idJoueur'])) ? "<h4 style='font-weight:bold;margin:5px;''>" . afficherMontant(Database::getSoldeJoueur($idJoueur)) . "</h4>": "";
        ?>
      </div>
      <div id="panier">box2 panier

      </div>
      <div id="boutonPanier">
        <a href="http://167.114.152.54/~darquest6/panier.php">
          <button type="button" class="buttonMarket">Afficher Panier</button>
        </a>
        <a href="http://167.114.152.54/~darquest6/payerPanier.php">
          <button type="button" class="buttonMarket">Payer Panier</button>
        </a>
      </div>
    </div>
  </div>
</main>
</body>

</html>