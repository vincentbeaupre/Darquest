<!DOCTYPE html>
<html lang="fr">

<head>
  <title>Panier</title>
  <link rel="stylesheet" href="stylesheet.css">
  <script src="fonctionJavaScript.js"></script>
  <?php require 'fonction.php'; ?>
  <?php require 'fonctionPanier.php'; ?>
</head>

<body class="panierBody">
  <?php include "header.php" ?>
  <h1>Panier</h1>
  <div id="itemPanier">
  <?php afficherPanier()?>

  </div>
</body>

</html>