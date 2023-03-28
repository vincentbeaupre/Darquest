<?php
require_once 'bd.php';

session_start();
$erreurs = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

  $nom = trim($_POST['nom']);
  $prenom = trim($_POST['prenom']);
  $alias = trim($_POST['alias']);

  if (!empty(trim($_POST['motDePasse']))) {
    $motDePasse = password_hash($_POST['motDePasse'], PASSWORD_DEFAULT);
  }

  $courriel = trim($_POST['courriel']);

  if (empty($nom)) {
    $erreurs['nom'] = "Nom requis";
  }
  if (empty($prenom)) {
    $erreurs['prenom'] = "Prénom requis";
  }
  if (empty($alias)) {
    $erreurs['alias'] = "Alias requis";
  }
  if (empty(trim($_POST['motDePasse']))) {
    $erreurs['motDePasse'] = "Mot de passe requis";
  }

  if (Database::checkAlias($alias) > 0) {
    $erreurs['alias'] = "Alias non disponible";
  }

  if (count($erreurs) === 0) {
    if (Database::addJoueur($alias, $nom, $prenom, $motDePasse, $courriel)) {
      header('location: login.php');
    }

  }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="stylesheet.css">
</head>

<?php
include 'header.php' ?>
<main>
  <div>
    <legend style="text-align:center;">
      Inscription
    </legend>
    <form class=loginForm method="POST">
      <label for="nom">Nom: </label>
      <input type="text" name="nom" id="nom" value="<?php echo !empty($_POST['nom']) ? $_POST['nom'] : '' ?>" required>
      <label for="prenom">Prénom: </label>
      <input type="text" name="prenom" id="prenom" value="<?php echo !empty($_POST['prenom']) ? $_POST['prenom'] : '' ?>" required>
      <label for="alias">Alias: </label>
      <input type="text" name="alias" id="alias" value="<?php echo !empty($_POST['alias']) ? $_POST['alias'] : '' ?>" required>
      <label for="motDePasse">Mot de passe: </label>
      <input type="password" name="motDePasse" id="motDePasse" required>
      <label for="courriel">Adresse courriel: </label>
      <input type="email" name="courriel" id="courriel" value="<?php echo !empty($_POST['courriel']) ? $_POST['courriel'] : '' ?>" required>
      <input type="submit" class="button" name="inscription_btn" value="Inscription">

    </form>
  </div>

  <?php if (count($erreurs) > 0) : ?>
    <div class="erreur">
      <?php foreach ($erreurs as $erreur) : ?>
        <li>
          <?= $erreur ?>
        </li>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

</main>
</body>

</html>