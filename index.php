<?php
session_start();

//Permet de voir si l'utilisateur vient de se déconnecté
$logout = false;
if ($_SERVER['REQUEST_METHOD'] === "GET") {
  if (isset($_GET['logout'])) {
    $logout = true;
  }
}
?>

<!DOCTYPE html>
<html lang="fr">

<?php 
  include "header.php"; 
  ?>
<main>
  <button onclick="launchSnackbar()">Show Snackbar</button>
  <?php
  if ($logout) {
    /*
    echo '
    <script type="text/javascript">launchSnackbar()</script>
    ';*/
    echo '<script type="text/javascript">alert("Vous êtes déconnectés.");</script>';
  }
  ?>
  <div id="snackbar">Vous êtes déconnectés.</div>
  <div class="welcome_title">
    <div class="smaller">
      Bienvenue sur
      <div>
        <div class="bigger">
          DarQuest
        </div>
</main>
</body>

</html>