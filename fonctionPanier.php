<?php 
require_once "bd.php";


function afficherPanier($idJoueur)
{
$result = Database::getPAnier($idJoueur);
echo $result;
}
function supprimerItemPanier($idJoueur,$idItem)
{
// appeler procédure stocker supprimer l'item du panier selon l'id du joueur
}
function modifierQuantitéItemPanier($idJoueur,$idItem,$quantité)
{
// appeler procédure sotcler modifierQuantité selon idjoueur sur l'item, vérifier si la quantité demander est en stock
}

?>