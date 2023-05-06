<?php
require_once('fonctions.php');
session_start();

(isset($_SESSION['idJoueur'])) ? $idJoueur = $_SESSION['idJoueur'] : "";

if ($_SERVER['REQUEST_METHOD'] === "GET") {
  (isset($_GET['idItem'])) ? $idItem = $_GET['idItem'] : "";
  (isset($_GET['typeItem'])) ? $typeItem = $_GET['typeItem'] : "";
  if (isset($_GET['idCommentaire'])) {
    if (Database::supprimerCommentaire($_GET['idCommentaire'])) {
      $_SESSION['message'] = "Le commentaire a été supprimé.";
    } else {
      $_SESSION['message'] = "Il semble y avoir eu une erreur lors de la supression de votre commentaire.";
    }
  }
} else if ($_SERVER['REQUEST_METHOD'] === "POST") {
  $idItem = $_POST['idItem'];
  $typeItem = $_POST['typeItem'];
  if (Database::ajouterCommentaire($_SESSION['idJoueur'], $idItem, $_POST['commentaire'])) {
    $_SESSION['message'] = "Merci pour votre commentaire.";
  } else {
    $_SESSION['message'] = "Il semble y avoir eu une erreur lors de l'ajout de votre commentaire.";
  }
}

if (isset($_SESSION['message'])) {
  $message = $_SESSION['message'];
  unset($_SESSION['message']);
}

$listCommentaire = Database::getAllCommentaireByItemId($idItem);
$moyenneEtoiles = Database::getMoyenneEvaluation($idItem);
$nbEvaluations = Database::getStatsEvaluation($idItem);
$totalEval = Database::getTotalEvaluation($idItem)['nbEvaluation'];
?>

<!DOCTYPE html>
<html lang="fr">

<?php include "header.php" ?>

<main>
  <?php if (isset($message)) : ?>
    <div id="snackbar"><?= $message ?></div>
  <?php endif; ?>
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
      <span>
        <form action="" method="POST" id="commForm">
          <h5>Ajouter un commentaire:</h5>
          <textarea id="commentaire" name="commentaire" rows="3" cols="50" minlength="0" maxlength="200" form="commForm" required></textarea>
          <input type="hidden" name="idItem" value="<?= $idItem ?>">
          <input type="hidden" name="typeItem" value="<?= $typeItem ?>">
          <button id="btnSubmit" type="submit">
            <i class="fa fa-sign-in"></i>
          </button>
        </form>
      </span>
    <?php } ?>

  </div>
  <div>
    <div class="rating">
      <?php for($x = 0; $x <= 5; $x++){
        if($x < floor($moyenneEtoiles['moyenneEvaluation'])){
          echo "<span class='star selected'>&#9733;</span>";
        }else{
          echo "<span class='star'>&#9733;</span>";
        }
      }
      echo "il y a " . floor($moyenneEtoiles['moyenneEvaluation']) . " evaluations.";
       ?>
    </div>
    <?php if($totalEval > 0){?>
    <div style="width: 10%;">
      <div class="barreEvaluations">
        <div style="background-color:gold;width:10%;">1&#9733;</div>
      </div>
      <div class="barreEvaluations">
        <div style="background-color:gold;width:<?= ($nbEvaluations[1] / $totalEval) ?>%;">2&#9733;</div>
      </div>
      <div class="barreEvaluations">
        <div style="background-color:gold;width:<?= ($nbEvaluations[2] / $totalEval) ?>%;">3&#9733;</div>
      </div>
      <div class="barreEvaluations">
        <div style="background-color:gold;width:<?= ($nbEvaluations[3] / $totalEval) ?>%;">4&#9733;</div>
      </div>
      <div class="barreEvaluations">
        <div style="background-color:gold;width:<?= ($nbEvaluations[4] / $totalEval) ?>%;">5&#9733;</div>
      </div>
    </div>
    <?php };?>
  </div>
  <div class="cartContainer">
    <h3 style="text-align:center">Commentaire:</h3>
    <div class="itemsContainer">
      <?php if (sizeof($listCommentaire) > 0) : ?>
        <?php
        foreach ($listCommentaire as $commentaire) {
        ?>
          <div class="itemContainer commentaireContainer">
            <span><?= $commentaire['alias'] ?></span>
            <span><?= $commentaire['commentaire'] ?></span>
            <?php if (isset($_SESSION['idJoueur'])) {
              if ($commentaire['idJoueur'] == $_SESSION['idJoueur'] || $_SESSION['estAdmin']) : ?>
                <form method="GET" id="deleteForm">
                  <input type="hidden" name="idCommentaire" value="<?= $commentaire['idCommentaire'] ?>">
                  <input type="hidden" name="idJoueur" value="<?= $commentaire['idJoueur'] ?>">
                  <input type="hidden" name="idItem" value="<?= $idItem ?>">
                  <input type="hidden" name="typeItem" value="<?= $typeItem ?>">
                  <button id='btnSubmit' type='submit'>
                    <i class='fa fa-trash fa-2x' style="color:red;"></i>
                  </button>
                </form>
            <?php endif;
            } ?>

          </div>
        <?php } ?>
      <?php else : ?>
        <div>
          Il n'y a aucun commentaire.
        </div>
      <?php endif; ?>
</main>
<script>
  //Message du SnackBar
  if (document.getElementById("snackbar") != null) {
    var snackbar = document.getElementById("snackbar");
    snackbar.classList.add("show");
    setTimeout(function() {
      snackbar.classList.remove("show");
    }, 3000);
  }

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