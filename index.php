<?php
session_start();

//Permet de voir si l'utilisateur vient de se déconnecté
if (isset($_SESSION['message'])) {
  $message = $_SESSION['message'];
  unset($_SESSION['message']);
}

?>

<!DOCTYPE html>
<html lang="fr">

<?php 
  include "header.php"; 
  ?>
<main>
<?php if (isset($message)) : ?>
    <div id="snackbar"><?= $message ?></div>
  <?php endif; ?>
  <div class="welcome_title">
    <div class="smaller">
      Bienvenue sur
      <div>
        <div class="bigger">
          DarQuest
        </div>
</main>
<script>
if (document.getElementById("snackbar") != null) {
  var snackbar = document.getElementById("snackbar");
  snackbar.classList.add("show");
  setTimeout(function(){ snackbar.classList.remove("show"); }, 3000);
}
</script>
</body>

</html>