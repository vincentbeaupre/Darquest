<?php 
require_once "bd.php";

function afficherMontant($montant) {
  $or = floor($montant / 100);
  $argent = floor(($montant % 100) / 10);
  $bronze = $montant % 10;

    return "$or&nbsp<i class='fa fa-circle' style='color: #FFD700'></i>
    &nbsp$argent&nbsp<i class='fa fa-circle' style='color: #C0C0C0'></i>
    &nbsp$bronze&nbsp<i class='fa fa-circle' style='color: #CD7F32'></i>";
}
