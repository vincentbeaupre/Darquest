<?php 
require_once "bd.php";


function afficherPanier($idJoueur)
{
$result = Database::getPAnier($idJoueur);
echo $result;
}

function afficherMontant($montant) {
  $or = floor($montant / 100);
  $argent = floor(($montant % 100) / 10);
  $bronze = $montant % 10;

    echo "<div>
          $or <i class='fa fa-circle' style='color: #FFD700'></i> 
          $argent <i class='fa fa-circle' style='color: #C0C0C0'></i>
          $bronze <i class='fa fa-circle' style='color: #CD7F32'></i>
          </div>";
}
?>