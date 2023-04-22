<?php
require_once('bd.php');
session_start();

if (isset($_SESSION['idJoueur'])) {
  $idJoueur = $_SESSION['idJoueur'];
} else {
  $_SESSION['message'] = "Connectez-vous pour accéder à Enigma";
  header('Location: index.php');
}

if ($_SERVER['REQUEST_METHOD'] === "GET") {

  $difficulte = $_GET['difficulte'];

  if ($difficulte == 'A') {
    $question = Database::getQuestionAleatoire($idJoueur);
  } else {
    $question = Database::getQuestionDifficulte($idJoueur, $difficulte);
  }

  $_SESSION['question'] = $question;

  $reponses = Database::getReponses($question['idQuestion']);
  $_SESSION['reponses'] = $reponses;

  $_SESSION['answered'] = false;
}

if ($_SERVER['REQUEST_METHOD'] === "POST") {

  $question = $_SESSION['question'];
  $reponses = $_SESSION['reponses'];
  $reponseJoueur = $_POST['idReponse'];
  $bonneReponse = Database::getBonneReponse($question['idQuestion']);

  if (!$_SESSION['answered']) {

    if ($reponseJoueur == $bonneReponse) {
    } else {
    }
    //! Update les tables (Joueur_Question & soldes de Joueur) selon le return de la fonction de vérification

    $_SESSION['answered'] = true;
  } else {
    $_SESSION['message'] = "Vous ne pouvez répondre qu'une seule fois à chaque question";
  }
}

if (isset($_SESSION['message'])) {
  $message = $_SESSION['message'];
  unset($_SESSION['message']);
}
?>

<!DOCTYPE html>
<html lang="fr">

<?php include_once("header.php"); ?>

<?php if (isset($message)) : ?>
  <div id="snackbar"><?= $message ?></div>
<?php endif; ?>

<main class="enigmaContainer">
  <div class="row">
    <div class="col-1"></div>
    <div class="col-10"><?= $question['enonce'] ?></div>
    <div class="col-1"></div>
  </div>

  <?php foreach ($reponses as $reponse) { ?>

    <div class="row">
      <div class="col-3"></div>
      <form method="POST">

        <input type="hidden" name="idReponse" value="<?= $reponse['idReponse'] ?>">
        <button type='submit' class="col-6 btnQuestion" <?php if ($_SESSION['answered']) {echo "disabled";} ?>>
          <?= $reponse['reponse'] ?>
          <?php if ($_SERVER['REQUEST_METHOD'] === "POST") { ?>
            <?php if ($reponse['idReponse'] == $bonneReponse) { ?>
              <i class="correctAnswer fa fa-check fa-lg"></i>
            <?php } else if ($reponse['idReponse'] == $reponseJoueur) { ?>
              <i class="incorrectAnswer fa fa-times fa-lg"></i>
            <?php } ?>
          <?php } ?>
        </button>
      </form>
      <div class="col-3"></div>
    </div>

  <?php } ?>

  <div class="row">
    <div class="col-12"></div>
  </div>
  <!--
  <div class="row">
    <div class="col-12">
      <a style='text-decoration: none; color: black' href='market.php'>
        <i class='fa fa-arrow-left fa-2x' style='padding:10px;'></i>
      </a>
    </div>
  </div>
-->
</main>
<script>
  if (document.getElementById("snackbar") != null) {
    var snackbar = document.getElementById("snackbar");
    snackbar.classList.add("show");
    setTimeout(function() {
      snackbar.classList.remove("show");
    }, 3000);
  }
</script>
</body>

</html>