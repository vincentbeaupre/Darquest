<?php
include 'bd.php';
session_start();

(isset($_SESSION['idJoueur'])) ? $idJoueur = $_SESSION['idJoueur'] : "";
if ($_SERVER['REQUEST_METHOD'] === "GET") {
    (isset($_GET['idItem'])) ? $idItem = $_GET['idItem'] : "";
    (isset($_GET['typeItem'])) ? $typeItem = $_GET['typeItem'] : "";
}
?>

<!DOCTYPE html>
<html lang="fr">

<?php include "header.php" ?>

<main>
<div class="itemDetail">
<?php
if(isset($idItem)&& isset($typeItem)){
    Database::getItemDetails($idItem,$typeItem);
}else{
    echo 'Il semble y avoir une erreur. Veuillez contactez un administrateur';
}
?>
</div>
</main>
</body>

</html>