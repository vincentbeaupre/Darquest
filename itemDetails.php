<?php
require_once('fonctions.php');
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
    if (isset($idItem) && isset($typeItem)) {
      $item = Database::getItemDetails($idItem, $typeItem);
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
      <span>Type: <?= $item['typeItem'] ?></span>
      <img src='<?= $item['photo'] ?>' width='128' height='128' style='border:3px black solid;border-radius:10px;'>
      <h1><?= $item['nom'] ?></h1>
      <span>Stock: <?= $item['quantiteStock'] ?></span>
      <span><?= $nomColonnes[0] ?> <?= $item[6] ?></span>
      <span><?= $nomColonnes[1] ?> <?= $item[7] ?></span>
      <?php
      if ($typeItem == 'Armes') {
        echo "<span>Genre: " . $item['genre'] . "</span>";
      }
      ?>
      <span>Prix: <?= $montant ?></span>
      <span>
        <a style='text-decoration: none; color: #ffffff' href='market.php'>
          <i class='fa fa-arrow-left fa-2x' style='padding:10px;'></i>
        </a>
      </span>
    <?php
    } else {
      echo 'Il semble y avoir une erreur. Veuillez contactez un administrateur';
    }
    ?>
    <?php
    //Ajout d'item au panier.
    if (isset($_SESSION['idJoueur'])) {

      if ($typeItem == 'Sorts') {
        if ($_SESSION['estMage'] == 1) {
          echo formAjouterItemPanier($item['idItem'], $item['quantiteStock']);
        } else {
          echo "<span>Les sorts peuvent seulement être achetés par un mage.</span>";
        }
      } else {
        echo formAjouterItemPanier($item['idItem'], $item['quantiteStock']);
      }
    } else {
      echo "<span> Afin d'ajouter un item à votre panier et l'acheter, veuillez vous connecter ! </span>";
    }

    if (isset($_SESSION['idJoueur']) && Database::estDansInventaire($idJoueur, $idItem)) { ?>
      <form action="submit_rating.php" method="post">
        <div class="rating">
          <span class="star" data-value="1">&#9733;</span>
          <span class="star" data-value="2">&#9733;</span>
          <span class="star" data-value="3">&#9733;</span>
          <span class="star" data-value="4">&#9733;</span>
          <span class="star" data-value="5">&#9733;</span>
          <input type="hidden" name="rating" id="rating" value="">
        </div>
        <button type="submit">Évaluer</button>
      </form>
    <?php } ?>

  </div>


  <div class="itemDetail">
    <h3>Commentaire</h3>


    <?php
    $result = Database::getAllCommentaireByItemId($idItem);

    foreach ($result as $comment) {
      echo "<div class='itemContainer'>";

      echo "<span class='commentaireNom'>";
      echo  Database::getAliasByIdJoueur($comment['idJoueur']) . " : ";
      echo "</span>";

      echo "<span class='commentaireContent'>";
      echo $comment['commentaire'] . " zzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzz";
      echo "</span>";

      echo "</div>";
    }
    ?>
  </div>
</main>
<script>
  // Add event listener to stars
  const stars = document.querySelectorAll('.star');
  stars.forEach(star => {
    star.addEventListener('click', () => {
      // Set value of hidden input field
      const ratingInput = document.getElementById('rating');
      const ratingValue = star.dataset.value;
      ratingInput.value = ratingValue;
      // Highlight selected star
      stars.forEach((s, i) => {
        if (i < ratingValue) {
          s.classList.add('selected');
        } else {
          s.classList.remove('selected');
        }
      });
    });
  });
</script>
</body>

</html>