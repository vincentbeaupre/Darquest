<?php
session_start();

if (!isset($_SESSION['idJoueur']))
  header('Location: index.php');
  
require_once('bd.php');
$items = Database::getInventaire($_SESSION['idJoueur']);

?>

<?php include "header.php" ?>

<main>
  <div class="cartContainer">
    <div class="itemsContainer">
      <?php if (sizeof($items) > 0) : ?>
        <?php
        foreach ($items as $item) {
        ?>
          <div class="itemContainer" onMouseOver="this.style.cursor='pointer'" onclick="location='http://167.114.152.54/~darquest6/itemDetails.php?idItem=<?= $item['idItem'] ?>&typeItem=<?= $item['typeItem'] ?>'">
              <img src=<?= $item['photo'] ?>></img>
              <span><?= $item['nom'] ?></span>
              <span>Qté: <?= $item['quantiteInventaire'] ?></span>
              <span><?= afficherMontant($item['prix']) ?></span>
          </div>
        <?php } ?>
      <?php else : ?>
        <div>
          Votre inventaire est vide
        </div>
      <?php endif; ?>
</main>


</body>

</html>