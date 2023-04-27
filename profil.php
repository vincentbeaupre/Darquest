<?php
session_start();

if (!isset($_SESSION['idJoueur']))
  header('Location: index.php');

require_once('bd.php');
?>
<?php include "header.php" ?>


<body>
  <main>

    <div id="modifierProfil">
      <form method="POST" action="profil.php">
        <?php
        if ($_SERVER['REQUEST_METHOD'] === "POST") {
          /* ne pas oublier de changer les variables de session apres changement sinon il se font seulement apres un log out */
          if ($_SESSION['nom'] != $_POST['nom']) {
            Database::changerNom($_SESSION['alias'],$_POST['nom']);
          }
          if ($_SESSION['prenom'] != $_POST['prenom']) {
            Database::changerPrenom($_SESSION['alias'],$_POST['prenom']);
          }
          if ($_SESSION['courriel'] != $_POST['courriel']) {
            Database::ChangerCourriel($_SESSION['alias'],$_POST['courriel']);
          }
          if(isset($_POST['pass']))
          { 
            $motDePasse = password_hash($_POST['pass'], PASSWORD_DEFAULT);
            Database::ChangerPassword($_SESSION['alias'],$motDePasse);
          }
          echo "<br>";
        }
        ?>

        <?php
        if ($_SESSION['estMage']) {
          echo "<h4>Status: Mage";
        } else {
          echo "<h4>Status:  Non Mage";
        }
        echo "<br>";
        echo "<br>";
        ?>

        <h4>Alias: <?php echo $_SESSION['alias']; ?></h4>
        <br>


        <label for="nom">Nom:</label>
        <br>
        <input type="text" id="nom" name="nom" value="<?php echo $_SESSION['nom']; ?>" maxlength="45" minlength="1" required>
        <br>
        <br>

        <label for="prenom">Prénom:</label>
        <br>
        <input type="text" id="prenom" name="prenom" value="<?php echo $_SESSION['prenom']; ?>" maxlength="45" minlength="1" required>
        <br>
        <br>

        <label for="courriel">Courriel:</label>
        <br>
        <input type="email" id="courriel" name="courriel" value="<?php echo $_SESSION['courriel']; ?>" maxlength="100" minlength="1" required>
        <br>
        <br>

        <label for="password">Password:</label>
        <br>
        <input type="password" name="pass">
        <br>
        <br>

        <input type="submit" value="Submit">
      </form>
    </div>






  </main>
</body>