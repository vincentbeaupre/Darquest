<?php
session_start();
require_once('bd.php');

$items = Database::getPanier($_SESSION['idJoueur']);
$numItemsInCart = Database::getNumItemsInCart($_SESSION['idJoueur']);
$total = 0;

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

          $total += $item['prix'] * $item['quantiteAchat'];
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
      </div>
    <?php endif; ?>
  </div>
</main>

</body>

</html>