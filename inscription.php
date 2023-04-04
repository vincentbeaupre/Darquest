<?php
require_once 'bd.php';

session_start();
$nomErr = $prenomErr = $aliasErr = $motDePasseErr = '';
$formValid = true;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

  $nom = trim($_POST['nom']);
  $prenom = trim($_POST['prenom']);
  $alias = trim($_POST['alias']);

  if (!empty(trim($_POST['motDePasse']))) {
    $motDePasse = password_hash($_POST['motDePasse'], PASSWORD_DEFAULT);
  }

  $courriel = trim($_POST['courriel']);

  if (empty($nom)) {
    $nomErr = "Nom requis";
    $formValid = false;
  }
  if (empty($prenom)) {
    $prenomErr = "Prénom requis";
    $formValid = false;
  }
  if (empty($alias)) {
    $aliasErr = "Alias requis";
    $formValid = false;
  }
  if (empty(trim($_POST['motDePasse']))) {
    $motDePasseErr = "Mot de passe requis";
    $formValid = false;
  }

  if (Database::checkAlias($alias) > 0) {
    $aliasErr = "Alias non disponible";
    $formValid = false;
  }

  if ($formValid) {
    if (Database::addJoueur($alias, $nom, $prenom, $motDePasse, $courriel)) {
      $_SESSION['message'] = "Inscription réussie";
      header('location: login.php');
    }
  }
}
?>

<!DOCTYPE html>
<html lang="fr">

<?php include 'header.php' ?>
<main>
  <div>
    <legend style="text-align:center;">
      Inscription
    </legend>
    <form class=loginForm method="POST">
      <label for="nom">Nom: </label>
      <input type="text" name="nom" id="nom" value="<?php echo !empty($_POST['nom']) ? $_POST['nom'] : '' ?>" required>
      <div class="erreur"><?= $nomErr ?></div>
      <label for="prenom">Prénom: </label>
      <input type="text" name="prenom" id="prenom" value="<?php echo !empty($_POST['prenom']) ? $_POST['prenom'] : '' ?>" required>
      <div class="erreur"><?= $prenomErr ?></div>
      <label for="alias">Alias: </label>
      <input type="text" name="alias" id="alias" value="<?php echo !empty($_POST['alias']) ? $_POST['alias'] : '' ?>" required>
      <div class="erreur"><?= $aliasErr ?></div>
      <label for="motDePasse">Mot de passe: </label>
      <input type="password" name="motDePasse" id="motDePasse" required>
      <div class="erreur"><?= $motDePasseErr ?></div>
      <label for="courriel">Adresse courriel: </label>
      <input type="email" name="courriel" id="courriel" value="<?php echo !empty($_POST['courriel']) ? $_POST['courriel'] : '' ?>" required>
      <input type="submit" class="button" name="inscription_btn" value="Inscription">
    </form>
  </div>
</main>
</body>

</html>