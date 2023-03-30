<?php session_start(); ?>
<!DOCTYPE html>
<html lang="fr">

<head>
  <title>Panier</title>
  <link rel="stylesheet" href="stylesheet.css">
  <script src="fonctionJavaScript.js"></script>
  <?php require_once 'fonction.php'; ?>
  <?php require_once 'fonctionPanier.php'; ?>
</head>
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