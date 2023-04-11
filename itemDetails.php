<?php
session_start();

(isset($_SESSION['idJoueur'])) ? $idJoueur = $_SESSION['idJoueur'] : "";
if ($_SERVER['REQUEST_METHOD'] === "GET") {
    (isset($_GET['idItem'])) ? $idItem = $_GET['idItem'] : "";
    (isset($_GET['typeItem'])) ? $typeItem = $_GET['typeItem'] : "";
}
?>

<!DOCTYPE html>
<html lang="fr">

<?php include "header.php" ?>

<main>
<div class="itemDetail">
<?php
if(isset($idItem)&& isset($typeItem)){
    $item = Database::getItemDetails($idItem,$typeItem);
    switch ($typeItem) {
        case 'Armes':
          $nomColonnes = ['Description: ', 'Efficacité: '];
          break;
        case 'Armures':
          $nomColonnes = ['Taille: ', 'Matière: '];
          break;
        case 'Sorts':
          $nomColonnes = ['Instantanéité: ', 'Nombre de point de vie: '];
          break;
        case 'Potions':
          $nomColonnes = ['Durée: ', 'Effet: '];
          break;
      }
    $montant = afficherMontant($item['prix']);
    ?>
    <span>Type: <?=$item['typeItem']?></span>
      <img src='<?=$item['photo']?>' width='128' height='128' style='border:3px black solid;border-radius:10px;'>
      <h1><?=$item['nom']?></h1>
      <span>Stock: <?=$item['quantiteStock']?></span>
      <span><?=$nomColonnes[0]?> <?=$item[6]?></span>
      <span><?=$nomColonnes[1]?> <?=$item[7]?></span>
      <?php
      if ($typeItem == 'Armes') {
        echo "<span>Genre: " . $item['genre'] . "</span>";
      }
      ?>
      <span>Prix: <?=$montant?></span>
      <span>
      <a style='text-decoration: none; color: #ffffff' href='market.php'>
      <i class='fa fa-arrow-left fa-2x' style='padding:10px;'></i>
      </a>
      </span>
      <?php if(isset($_SESSION['idJoueur'])){?>
        <span>
        Ajouter l'item au panier:
        </span><span>
        <form method='POST' action='market.php'>
          <input type='hidden' name='idItem' value='<?=$item['idItem']?>'>
          <label for='quantite'>Quantité (entre 1 et <?=$item['quantiteStock']?>):</label>
            <input type='number' id='quantite' name='quantite' min='1' max=<?=$item['quantiteStock']?>>
          <label for='btnSubmit'></label>
          <button id='btnSubmit' type='submit'>
            <i class='fa fa-plus'></i>
          </button>
        </form>
        </span>
      <?php }
}else{
    echo 'Il semble y avoir une erreur. Veuillez contactez un administrateur';
}
?>
</div>
</main>
</body>

</html>