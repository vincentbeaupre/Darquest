<?php session_start(); ?>
<!DOCTYPE html>
<html lang="fr">

<body class="panierBody">
  <?php include "header.php" ?>
  <h1>Panier</h1>
  <div id="itemPanier">
  <?php
  if(isset($_SESSION['idJoueur']))
  {
    afficherPanier($_SESSION['idJoueur']);
  }
   ?>

  </div>
</body>

</html>