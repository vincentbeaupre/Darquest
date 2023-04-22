<?php
require_once('bd.php');
session_start();

(isset($_SESSION['idJoueur'])) ? $idJoueur = $_SESSION['idJoueur'] : "";
if ($_SERVER['REQUEST_METHOD'] === "POST") {
  (isset($_POST['difficulte'])) ? $difficulte = $_POST['difficulte'] : "";
  if($difficulte == 'A'){
    $question = Database::getQuestionAleatoire($idJoueur);
  }else{
    $question = Database::getQuestionDifficulte($idJoueur,$difficulte);
  }
  $reponse = Database::getReponses($question['idQuestion']);

}else if($_SERVER['REQUEST_METHOD'] === "GET"){
    $reponse = Database::getReponses($_GET['idQuestion']);
    $question = Database::getQuestion($_GET['idQuestion']);
    //! Update les tables (Joueur_Question & soldes de Joueur) selon le return de la fonction de vérification
    //! Launch le snackbar
}
?>

<!DOCTYPE html>
<html lang="fr">

<?php 
include_once("header.php");
foreach($reponse as $idReponse){
}
?>

<main class="enigmaContainer">
  <div class="row">
    <div class="col-1"></div>
    <div class="col-10"><?= $question['enonce'] ?></div>
    <div class="col-1"></div>
  </div>
  <div class="row">
    <div class="col-3"></div>
    <form action="" method="GET">
        <input type="hidden" name="idQuestion" value="<?= $question['idQuestion']?>">
      <input type="hidden" name="idReponse" value="<?= $reponse[0]['idReponse']?>">
      <button type='submit' class="col-6 btnQuestion"><?php echo $reponse[0]['reponse'] ?>
      </button>
    </form>
    <div class="col-3"></div>
  </div>
  <div class="row">
    <div class="col-3"></div>
    <form action="" method="GET">
        <input type="hidden" name="idQuestion" value="<?= $question['idQuestion']?>">
      <input type="hidden" name="idReponse" value="<?= $reponse[1]['idReponse']?>">
      <button type='submit' class="col-6 btnQuestion"><?php echo $reponse[1]['reponse'] ?>
      </button>
    </form>
    <div class="col-3"></div>
  </div>
  <div class="row">
    <div class="col-3"></div>
    <form action="" method="GET">
        <input type="hidden" name="idQuestion" value="<?= $question['idQuestion']?>">
      <input type="hidden" name="idReponse" value="<?= $reponse[2]['idReponse']?>">
      <button type='submit' class="col-6 btnQuestion"><?php echo $reponse[2]['reponse'] ?>
      </button>
    </form>
    <div class="col-3"></div>
  </div>
  <div class="row">
    <div class="col-3"></div>
    <form action="" method="GET">
        <input type="hidden" name="idQuestion" value="<?= $question['idQuestion']?>">
      <input type="hidden" name="idReponse" value="<?= $reponse[3]['idReponse']?>">
      <button type='submit' class="col-6 btnQuestion"><?php echo $reponse[3]['reponse'] ?>
      </button>
    </form>
    <div class="col-3"></div>
  </div>
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
</body>

</html>