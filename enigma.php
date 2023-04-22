<?php
session_start();

?>

<!DOCTYPE html>
<html lang="fr">

<?php include "header.php" ?>

<main class="enigmaContainer">
  <div class="row">
    <div class="col-2"></div>
    <div class="col-8">Bienvenue sur Enigma ! Veuillez sélectionner une difficulté de questions:</div>
    <div class="col-2"></div>
  </div>
  <div class="row">
    <div class="col-4"></div>
    <form action="questions.php" method="POST">
      <input type="hidden" name="difficulte" value="A">
      <button type='submit' class="col-4 btnQuestion">Question aléatoire
      </button>
    </form>
    <div class="col-4"></div>
  </div>
  <div class="row">
    <div class="col-12">OU</div>
  </div>
  <div class="row">
    <div class="col-4"></div>
    <form action="questions.php" method="POST">
      <input type="hidden" name="difficulte" value="F">
      <button type='submit' class="col-4 btnQuestion">Question facile
      </button>
    </form>
    <div class="col-4"></div>
  </div>
  <div class="row">
    <div class="col-4"></div>
    <form action="questions.php" method="POST">
      <input type="hidden" name="difficulte" value="M">
      <button type='submit' class="col-4 btnQuestion">Question moyenne
      </button>
    </form>
    <div class="col-4"></div>
  </div>
  <div class="row">
    <div class="col-4"></div>
    <form action="questions.php" method="POST">
      <input type="hidden" name="difficulte" value="D">
      <button type='submit' class="col-4 btnQuestion">Question difficile
      </button>
    </form>
    <div class="col-4"></div>
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
</main>
</body>

</html>