<?php
@session_start();
require_once("bd.php");

$erreur = "";

if (isset($_SESSION['alias'])) {
  session_destroy();
  session_unset();
  setcookie("PHPSESSID", "", -1);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  if (Database::validerJoueur($_POST['alias'], $_POST['motDePasse'])) {
    header("Location: index.php");
  } else {
    $erreur = "<span class='erreur'>Données de connexion non valides</span>";
  }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="stylesheet.css">
</head>

<?php include 'header.php' ?>
<?php
if (isset($_SESSION['message'])) {
  echo "<div>" . $_SESSION['message'] . "</div>";
  unset($_SESSION['message']);
}
?>
<main>
  <div class="loginBox">
    <legend style="text-align:center;">
      Connexion
    </legend>
    <form class="loginForm" method="POST">
      <?= $erreur ?>
      <label for="alias">Alias: </label>
      <input type="text" name="alias" id="alias" required>
      <label for="motDePasse">Mot de passe: </label>
      <input type="password" name="motDePasse" id="motDePasse" required>
      <input class="button" type="submit" name="connexion_btn" value="Connexion">
    </form>

  </div>
  <p style="text-align:center;">
    <a href="inscription.php">Inscription</a>
  </p>
</main>
</body>