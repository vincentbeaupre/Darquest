<?php
session_start();
include_once "bd.php";

if (isset($_SESSION['idJoueur'])) {
  $idJoueur = $_SESSION['idJoueur'];
} else {
  $_SESSION['message'] = "Connectez-vous pour accéder à Enigma";
  header('Location: index.php');
}

if (isset($_SESSION['message'])) {
  $message = $_SESSION['message'];
  unset($_SESSION['message']);
}

$totalBonneRep = Database::getTotalBonneRep($_SESSION['idJoueur']);
$totalNbQuestions = Database::getNombresQuestion();
?>

<!DOCTYPE html>
<html lang="fr">

<?php include_once "header.php" ?>

<?php if (isset($message)) : ?>
  <div id="snackbar"><?= $message ?></div>
<?php endif; ?>

<main class="enigmaContainer">
  <div class="row">
    <div class="col-2"></div>
    <div class="col-8">Bienvenue sur Enigma ! Veuillez sélectionner une difficulté de questions:</div>
    <div class="col-2"></div>
  </div>
  <div class="row">
    <div class="col-3"></div>
    <form action="questions.php" method="GET">
      <input type="hidden" name="difficulte" value="A">
      <button type='submit' class="col-6 btnQuestion">Question aléatoire
      </button>
    </form>
    <div class="col-3"></div>
  </div>
  <div class="row">
    <div class="col-12">OU</div>
  </div>
  <div class="row">
    <div class="col-3"></div>
    <form action="questions.php" method="GET">
      <input type="hidden" name="difficulte" value="F">
      <button type='submit' class="col-6 btnQuestion">Question facile
      </button>
    </form>
    <div class="col-3"></div>
  </div>
  <div class="row">
    <div class="col-3"></div>
    <form action="questions.php" method="GET">
      <input type="hidden" name="difficulte" value="M">
      <button type='submit' class="col-6 btnQuestion">Question moyenne
      </button>
    </form>
    <div class="col-3"></div>
  </div>
  <div class="row">
    <div class="col-3"></div>
    <form action="questions.php" method="GET">
      <input type="hidden" name="difficulte" value="D">
      <button type='submit' class="col-6 btnQuestion">Question difficile
      </button>
    </form>
    <div class="col-3"></div>
  </div>
  <div class="row">
    <div class="col-12"></div>
  </div>
  <div class="row">
    <div class="col-12">
      <a style='text-decoration: none; color: black' href='market.php'>
        <i class='fa fa-arrow-left fa-2x' style='padding:10px;'></i>
      </a>
    </div>
  </div>

  <div class="row">
    <div class="col-12">
      Progrès:
    </div>
  </div>
  <div class="row">
    <div class="col-3"></div>
    <div class="col-6 progressBox">
      <div>
        <label for="facile">Facile:</label>
        <progress name="facile" value="<?= $totalBonneRep[0][0] ?>" max="<?= $totalNbQuestions[0][0] ?>"></progress>
        <?php echo $totalBonneRep[0][0] . "/" . $totalNbQuestions[0][0] ?>
      </div>
      <div><label for="moyen">Moyen:</label>
        <progress name="moyen" value="<?= $totalBonneRep[1][0] ?>" max="<?= $totalNbQuestions[1][0] ?>"></progress>
        <?php echo $totalBonneRep[1][0] . "/" . $totalNbQuestions[1][0] ?>
      </div>
      <div>
        <label for="difficile">Difficile:</label>
        <progress name="difficile" value="<?= $totalBonneRep[2][0] ?>" max="<?= $totalNbQuestions[2][0] ?>"></progress>
        <?php echo $totalBonneRep[2][0] . "/" . $totalNbQuestions[2][0] ?>
      </div>
    </div>
    <div class="col-3"></div>
  </div>
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