<?php
session_start();

if (!isset($_SESSION['idJoueur']))
  header('Location: index.php');

if ($_SESSION['estAdmin'] != 1)
  header('Location: index.php');

require_once('bd.php');
?>
<?php include "header.php" ?>


<main>
<?php if(isset($_POST["submit"]))
{
    echo "<h1>test</test>";
}

?>
  <div id="ajoutQuestion">
   <h1>Ajouter une question dans Enigma</h1>

   <form method="Post">
  <label for="question">Question :</label><br>
  <input type="text" id="question" name="question" required><br>

  <label for="response1">Réponse 1 :</label><br>
  <input type="text" id="response1" name="response1" required><br>

  <label for="response2">Réponse 2 :</label><br>
  <input type="text" id="response2" name="response2" required><br>

  <label for="response3">Réponse 3 :</label><br>
  <input type="text" id="response3" name="response3" required><br>

  <label for="response3">Réponse 4 :</label><br>
  <input type="text" id="response4" name="response4" required><br>

  <label for="correct-answer">Bonne réponse :</label><br>
  <select id="correct-answer" name="correct-answer">
    <option value="response1">Réponse 1</option>
    <option value="response2">Réponse 2</option>
    <option value="response3">Réponse 3</option>
    <option value="response3">Réponse 4</option>
  </select><br>

  <label for="difficulty">Difficulté :</label><br>
  <select id="difficulty" name="difficulty">
    <option value="facile">Facile</option>
    <option value="moyen">Moyen</option>
    <option value="difficile">Difficile</option>
  </select><br><br>

  <input type="submit" name="submit" value="submit">
  <br>
</form>

  </div>
</main>


</body>

</html>