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

<body>
      <header>
        <h1><a href="index.php" class="header_link">Darquest</a></h1>
      </header>
      <?php
      if (isset($_SESSION['message'])) {
        echo "<div>" . $_SESSION['message'] . "</div>";
        unset($_SESSION['message']);
      }
      ?>
      <fieldset>
        <legend>
          Connexion
        </legend>
        <form method="POST">
          <table>
            <tr>
              <td><label for="alias">Alias: </label></td>
              <td><input type="text" name="alias" id="alias" value="<?php echo !empty($_POST['pseudo']) ? $_POST['pseudo'] : '' ?>" required></td>
            </tr>
            <tr>
              <td><label for="motDePasse">Mot de passe: </label></td>
              <td><input type="password" name="motDePasse" id="motDePasse" required></td>
            </tr>
            <tr>
              <td><input class="button" type="submit" name="connexion_btn" value="Connexion"></td>
            </tr>
          </table>
        </form>
        <?= $erreur ?>
      </fieldset>
      <p>
        <a class="link" href="inscription.php">Inscription</a>
      </p>