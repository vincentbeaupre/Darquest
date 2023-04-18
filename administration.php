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
<?php if(isset($_POST["submit"]) && isset($_SESSION['estAdmin']))
{
  $enonce = $_POST['question'];
  $difficulty = $_POST['difficulty'];
  $reponse1 = $_POST['response1'];
  $reponse2 = $_POST['response2'];
  $reponse3 = $_POST['response3'];
  $reponse4 = $_POST['response4'];
  $correctAnswer = $_POST['correct-answer'];

  echo "Question ajouter avec Succes";
  Database::ajouterQuestion($enonce,$difficulty,$reponse1,$reponse2,$reponse3,$reponse4,$correctAnswer);
 
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
    <option value="1">Réponse 1</option>
    <option value="2">Réponse 2</option>
    <option value="3">Réponse 3</option>
    <option value="4">Réponse 4</option>
  </select><br>

  <label for="difficulty">Difficulté :</label><br>
  <select id="difficulty" name="difficulty">
    <option value="F">Facile</option>
    <option value="M">Moyen</option>
    <option value="D">Difficile</option>
  </select><br><br>

  <input type="submit" name="submit" value="submit">
  <br>
</form>

  </div>
</main>


</body>

</html>