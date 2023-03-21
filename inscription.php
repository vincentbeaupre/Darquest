<?php
require_once 'bd.php';

session_start();
$erreurs = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

  $nom = trim($_POST['nom']);
  $prenom = trim($_POST['prenom']);
  $alias = trim($_POST['alias']);

  if (!empty(trim($_POST['password']))) {
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
  }

  $email = trim($_POST['email']);

  if (empty($nom)) {
    $erreurs['nom'] = "Nom requis";
  }
  if (empty($prenom)) {
    $erreurs['prenom'] = "Prénom requis";
  }
  if (empty($alias)) {
    $erreurs['alias'] = "Alias requis";
  }
  if (empty(trim($_POST['password']))) {
    $erreurs['password'] = "Mot de passe requis";
  }

  if (Database::checkAlias($alias) > 0) {
    $erreurs['alias'] = "Alias non disponible";
  }

  if (count($erreurs) === 0) {
    if (Database::addJoueur($alias, $nom, $prenom, $password, $email)) {
      $erreurs['inscription'] = "inscription ne fonctionne pas";
    }

    $_SESSION['alias'] = $alias;
    $_SESSION['nom'] = $nom;
    $_SESSION['prenom'] = $prenom;
    $_SESSION['password'] = $password;
    $_SESSION['email'] = $email;
    header('location: login.php');
  }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="style.css">
</head>

<body>
  <div id="page-container">
    <div id="content-wrap">
      <header>
        <h1><a href="index.php" class="header_link">Darquest</a></h1>
      </header>
      <fieldset>
        <legend>
          Inscription
        </legend>
        <form method="POST">
          <table>
            <tr>
              <td><label for="nom">Nom: </label></td>
              <td><input type="text" name="nom" id="nom" value="<?php echo !empty($_POST['nom']) ? $_POST['nom'] : '' ?>" required></td>
            </tr>
            <tr>
              <td><label for="prenom">Prénom: </label></td>
              <td><input type="text" name="prenom" id="prenom" value="<?php echo !empty($_POST['prenom']) ? $_POST['prenom'] : '' ?>" required></td>
            </tr>
            <tr>
              <td><label for="alias">Alias: </label></td>
              <td><input type="text" name="alias" id="alias" value="<?php echo !empty($_POST['alias']) ? $_POST['alias'] : '' ?>" required></td>
            </tr>
            <tr>
              <td><label for="password">Mot de passe: </label></td>
              <td><input type="password" name="password" id="password" required></td>
            </tr>
            <tr>
              <td><label for="email">Adresse courriel: </label></td>
              <td><input type="email" name="email" id="email" value="<?php echo !empty($_POST['email']) ? $_POST['email'] : '' ?>" required></td>
            </tr>
            <tr>
              <td><input type="submit" class="button" name="inscription_btn" value="Inscription"></td>
            </tr>
          </table>

        </form>
      </fieldset>
      <?php if (count($erreurs) > 0) : ?>
        <div class="erreur">
          <?php foreach ($erreurs as $erreur) : ?>
            <li>
              <?= $erreur ?>
            </li>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>