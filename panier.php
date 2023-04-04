<?php
session_start();
require_once('bd.php');

if (!isset($_SESSION['idJoueur']))
  header('Location: index.php');

  $message = "";

if (isset($_POST['payer_panier'])) {
  $idJoueur = $_SESSION['idJoueur'];
  $fonds = Database::getSoldeJoueur($idJoueur);

  if ($total <= $fonds) {
    Database::payerPanier($idJoueur) ? $message = "Achat effectué avec succès." : "Une erreur est survenue lors de l'achat.";
  } else {
    $message = "Fonds insuffisants pour effectuer le paiement.";
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
            <img src=<?= $item['photo'] ?>></img>
            <span><?= $item['nom'] ?></span>
            <span><?= afficherMontant($item['prix']) ?></span>
            <form method="post">
              <label for="quantiteAchat">Qté:</label>
              <input id="quantiteAchat<?= $item['idItem'] ?>" type='number' value=<?= $item['quantiteAchat'] ?> min="1" max=<?= $item['quantiteStock'] ?>>
              <input type="hidden" name="idArticle" value="<?= $item['idItem'] ?>" />
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
        <form method="post">
          <input type="submit" name="payer_panier" value="Payer panier">
        </form>
      </div>
    <?php endif; ?>
    <div>
      <?= $message ?>
    </div>
  </div>
</main>

</body>

</html>