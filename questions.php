<?php
require_once('bd.php');
session_start();

if (isset($_SESSION['idJoueur'])) {
  $idJoueur = $_SESSION['idJoueur'];
} else {
  $_SESSION['message'] = "Connectez-vous pour accéder à Enigma";
  header('Location: index.php');
  exit();
}

if ($_SERVER['REQUEST_METHOD'] === "GET") {

  $difficulte = $_GET['difficulte'];

  if ($difficulte == 'A') {
    $question = Database::getQuestionAleatoire($idJoueur);
  } else {
    $question = Database::getQuestionDifficulte($idJoueur, $difficulte);
  }

  if (!$question) {
    $_SESSION['message'] = "Aucune question disponible pour cette catégorie";
    header('Location: enigma.php');
    exit();
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
  $estBonneReponse = $reponseJoueur == $bonneReponse;

  if (!$_SESSION['answered']) {

    if (Database::answerQuestion($idJoueur, $question['idQuestion'], $estBonneReponse)) {

      if ($estBonneReponse) {

        switch ($question['difficulte']) {
          case "F":
            $gain = 10;
            $typePiece = "de bronze";
            break;
          case "M":
            $gain = 100;
            $typePiece = "d'argent";
            break;
          case "D":
            $gain = 1000;
            $typePiece = "d'or";
            break;
        }

        Database::modifierSolde($idJoueur, $gain);

        $_SESSION['message'] = "Bonne réponse! Vous gagnez 10 pièces " . $typePiece . ".";

        if (!$_SESSION['estMage'] && Database::getBonnesReponsesDifficiles($idJoueur) == "5") {

          Database::updateMageStatus($idJoueur);
          $_SESSION['estMage'] = true;
          $_SESSION['message'] .= " De plus, vous êtes désormais un MAGE!";
        }
      } else {

        $_SESSION['message'] = "Mauvaise réponse... Meilleure chance la prochaine fois";
      }

      $_SESSION['answered'] = true;
    } else {
      $_SESSION['message'] = "Erreur lors de la soumission de votre réponse";
    }
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
        <button type='submit' class="col-6 btnQuestion" <?php if ($_SESSION['answered']) {
                                                          echo "disabled";
                                                        } ?>>
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

  <?php if ($_SESSION['answered']) { ?>

    <div class="row">
      <div class="col-12">
        <input type="button" value="Rejouer" onclick="window.location.href='enigma.php'" />
      </div>
    </div>
  <?php } ?>
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