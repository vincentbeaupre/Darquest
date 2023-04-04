<?php
session_start();
require_once('bd.php');

if (!isset($_SESSION['idJoueur']))
  header('Location: index.php');

$message = "";

if ($_SERVER['REQUEST_METHOD'] === "POST") {

  if (isset($_POST['payer_panier'])) {
    $idJoueur = $_SESSION['idJoueur'];
    $items = Database::getPanier($idJoueur);
    $fonds = Database::getSoldeJoueur($idJoueur);
    $total = 0;

    foreach ($items as $item) {
      $total += $item['prix'] * $item['quantiteAchat'];
    }

    if ($total <= $fonds) {

      if (Database::payerPanier($idJoueur)) {
        $message = "Achat effectué avec succès.";
      } else {
        $message = "Une erreur est survenue lors de l'achat.";
      }

    } else {
      $message = "Fonds insuffisants pour effectuer le paiement.";
    }

  } else {
    $idItem = $_POST['idItem'];
    $idJoueur = $_SESSION['idJoueur'];
    $action = $_POST['action'];
    if ($action == 'updateQuantite') {
      $quantite = $_POST['quantiteAchat'];
      if (Database::modifiéQuantitéItem($idJoueur, $idItem, $quantite)) {
        $messageAction = "<div class='marketSearch'>La quantité à été mis à jour.</div>";
      } else {
        $messageAction = "<div class='marketSearch'> Il y a eu une erreur lors du changement de la quantité dans le panier,
        veuillez réessayer ou contacter un administrateur</div>";
      }
    } elseif ($action == 'supprimerItem') {
      if (Database::supprimerFromPanier($idItem, $idJoueur)) {
        $messageAction = "<div class='marketSearch'>L'objet à été supprimer de votre panier !</div>";
      } else {
        $messageAction = "<div class='marketSearch'> Il y a eu une erreur lors de la suppresion de l'item au panier,
        veuillez réessayer ou contacter un administrateur</div>";
      }
    }
  }
}

$items = Database::getPanier($_SESSION['idJoueur']);
$numItemsInCart = Database::getNumItemsInCart($_SESSION['idJoueur']);
$total = 0;

foreach ($items as $item) {
  $total += $item['prix'] * $item['quantiteAchat'];
}

?>

<!DOCTYPE html>
<html lang="fr">

<?php include "header.php" ?>

<main>
  <div class="cartContainer">
    <div class="itemsContainer">
      <?php if (sizeof($items) > 0) : ?>
        <?php
        foreach ($items as $item) {
        ?>

          <div class="itemContainer">
            <form method="POST">
              <input type="hidden" name="idItem" value="<?= $item['idItem'] ?>" />
              <input type="hidden" name="action" value="supprimerItem" />
              <button id='btnSubmit' type='submit'>
                <i class='fa fa-trash fa-2x' style='color:red;'></i>
              </button>
            </form>
            <img src=<?= $item['photo'] ?>></img>
            <span><?= $item['nom'] ?></span>
            <span><?= afficherMontant($item['prix']) ?></span>
            <form method="post">
              <label for="quantiteAchat">Qté:</label>
              <input id="quantiteAchat<?= $item['idItem'] ?>" name='quantiteAchat' type='number' value=<?= $item['quantiteAchat'] ?> min="1" max=<?= $item['quantiteStock'] + $item['quantiteAchat'] ?>>
              <input type="hidden" name="idItem" value="<?= $item['idItem'] ?>" />
              <input type="hidden" name="action" value="updateQuantite" />
              <button id='btnSubmit' type='submit'>
                <i class='fa fa-check-circle fa-2x' style='color:green;'></i>
              </button>
            </form>
          </div>

        <?php } ?>

      <?php else : ?>
        <div>
          Votre panier est vide
        </div>
      <?php endif; ?>
    </div>
    <?php if (sizeof($items) > 0) : ?>
      <div class="cartSummary">
        Sous-total (<?= $numItemsInCart ?> <?= $numItemsInCart > 1 ? "articles" : "article" ?> ):
        <?= afficherMontant($total) ?>
      </div>
      <div>
        <form method="POST">
          <input type="submit" name="payer_panier" value="Payer panier">
        </form>
      </div>
    <?php endif; ?>
    <?php
    if (isset($messageAction)) {
      echo $messageAction;
    }
    ?>
    <div>
      <?= $message ?>
    </div>
  </div>
</main>

</body>

</html>