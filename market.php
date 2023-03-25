<?php
session_start();
?>

<!DOCTYPE html>
<html lang="fr">

<?php include "header.php" ?>

<main>
  <div id="marketPage">
    <div id="box1">
      <div id="marketItemList">

      </div>
      <div id="marketSearchBy">
        <h2>Recherche</h2>

        <h3>Filtrer par type</h3>
        <input type="checkbox" id="Armure" name="Armure" value="Armure">
        <label for="Armure"> Armures</label><br>
        <input type="checkbox" id="Armes" name="Armes" value="Armes">
        <label for="Armes"> Armes</label><br>
        <input type="checkbox" id="Potion" name="Potion" value="Potion">
        <label for="Potion"> Potion</label><br>
        <input type="checkbox" id="Sort" name="Sort" value="Sort">
        <label for="Sort"> Sort</label><br>
        <h3>Trier par</h3>
        <label for="poids">Poids:</label>
        <input type="button" id="poidsASC" name="trierPoids" value="↑">
        <input type="button" id="poidsDESC" name="trierPoids" value="↓">
        <br>
        <label for="poids">Prix:&nbsp&nbsp</label>
        <input type="button" id="prixASC" name="trierPrix" value="↑">
        <input type="button" id="prixDESC" name="trierPrix" value="↓">


      </div>
    </div>
    <div id="box2">
      <div id="currency">box2 currency

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