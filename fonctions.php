<?php
require_once "bd.php";

function afficherMontant($montant)
{
  $or = floor($montant / 100);
  $argent = floor(($montant % 100) / 10);
  $bronze = $montant % 10;

  return "$or&nbsp<i class='fa fa-circle' style='color: #FFD700'></i>
    &nbsp$argent&nbsp<i class='fa fa-circle' style='color: #C0C0C0'></i>
    &nbsp$bronze&nbsp<i class='fa fa-circle' style='color: #CD7F32'></i>";
}

function formAjouterItemPanier($idItem, $quantiteStock)
{

  return "<span>
Ajouter l'item au panier:
</span>
<span>
<form method='POST' action='market.php'>
  <input type='hidden' name='idItem' value=" . $idItem . ">
  <label for='quantite'>Quantité (entre 1 et " . $quantiteStock . "):</label>
  <input type='number' id='quantite' name='quantite' min='1' max=" . $quantiteStock . ">
  <label for='btnSubmit'></label>
  <button id='btnSubmit' type='submit'>
    <i class='fa fa-plus'></i>
  </button>
</form>
</span>";
}
