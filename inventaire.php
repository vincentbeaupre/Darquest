<?php
session_start();

if (!isset($_SESSION['idJoueur']))
  header('Location: index.php');
  
?>

<!DOCTYPE html>
<html lang="fr">

<body>
  <?php include "header.php" ?>

  <main>
    <div id="mainPage">
      <div id="inventaireMainContainer"></div>
      <div id="seachInventaireContainer"></div>
    </div>
  </main>


</body>

</html>