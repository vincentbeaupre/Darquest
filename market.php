<?php
require_once 'bd.php';
require_once('fonctions.php');
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
}elseif ($_SERVER['REQUEST_METHOD'] === "POST"){
  $idItem = $_POST['idItem'];
  $quantite = $_POST['quantite'];
  if (Database::ajouterItemPanier($idItem,$quantite,$idJoueur)){
    $messagePanier = "<div class='marketSearch'>Vous avez ajouté un objet à votre panier</div>";
  }else {
    $messagePanier = "<div class='marketSearch'> Il y a eu une erreur lors de l'ajout de l'item au panier.
    Si vous essayer de modifier la quantité, s'il vous plait vous diriger dans le panier. 
    Si l'erreur persite,veuillez contacter un administrateur</div>";
  }
}

$items = Database::getAllItems($prix, $type, $armes, $armures, $potions, $sorts);
?>

<!DOCTYPE html>
<html lang="fr">

<?php include "header.php" ?>

<main class="marketMain">
  <!-- Liste des Items -->
  <div class="itemCardMain">
    <?php if (sizeof($items) > 0) : ?>
        <?php
        foreach ($items as $item) {
          $montant = afficherMontant($item['prix']);
        ?>
      <div id=<?=$item['idItem']?>>
      <a class='itemCardChild' href='itemDetails.php?idItem=<?=$item['idItem']?>&typeItem=<?=$item['typeItem']?>'>
      <h4 style='font-weight:bold;margin:5px;'><?=$item['nom']?></h4>
      <img src="<?=$item['photo']?>" style='border:3px black solid;border-radius:10px;'>
      <span>Stock: <span><?=$item['quantiteStock']?></span></span>
      <span>Prix: <?=$montant?></span>
      <input type='hidden' id='idItem' name='idItem' value="<?=$item['idItem']?>" />
      <input type='hidden' id='typeItem' name='typeItem' value="<?=$item['typeItem']?>" />
      </a>
    </div>
        <?php } ?>
      <?php else : ?>
        <div>
          Le magasin est vide.
        </div>
      <?php endif; ?>
  </div>
  <!-- Recherche des Items-->
  <div class="marketSearch">
    <?php
    echo (isset($_SESSION['idJoueur'])) ? "<h4 style='font-weight:bold;margin:5px;''>" . afficherMontant(Database::getSoldeJoueur($idJoueur)) . "</h4>" : "";
    ?>
    <h2>Recherche</h2>
    <form action="" method="GET">
      <h3>Filtrer par type</h3>
      <input type="checkbox" id="Armure" name="Armure" value="Armure" <?php echo (isset($_GET['Armure'])) ? 'checked' : '' ?>>
      <label for="Armure"> Armures</label><br>
      <input type="checkbox" id="Armes" name="Arme" value="Arme" <?php echo (isset($_GET['Arme'])) ? 'checked' : '' ?>>
      <label for="Armes"> Armes</label><br>
      <input type="checkbox" id="Potion" name="Potion" value="Potion" <?php echo (isset($_GET['Potion'])) ? 'checked' : '' ?>>
      <label for="Potion"> Potion</label><br>
      <input type="checkbox" id="Sort" name="Sort" value="Sort" <?php echo (isset($_GET['Sort'])) ? 'checked' : '' ?>>
      <label for="Sort"> Sort</label><br>
      <h3>Trier par</h3>
      <label for="trierPrix">Prix:&nbsp&nbsp</label>
      <input type="radio" id="prixASC" name="trierPrix" value="asc"><i class="fa fa-arrow-up" aria-hidden="true"></i>
      <input type="radio" id="prixDESC" name="trierPrix" value="desc"><i class="fa fa-arrow-down" aria-hidden="true"></i>
      <br>
      <input class="button" type="submit" name="filtrage_btn" value="Recherche">
    </form>
    <a style="text-decoration:none;color:red;" href='market.php'>Réinitialiser</a>
  </div>
</main>
  <?php
  if (isset($messagePanier)){
    echo $messagePanier;
  }
  ?>
</body>

</html>