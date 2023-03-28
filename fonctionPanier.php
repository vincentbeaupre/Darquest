<?php 
require_once "bd.php";


function afficherPanier($idJoueur)
{
$result = Database::getPAnier($idJoueur);
echo $result;
}

?>