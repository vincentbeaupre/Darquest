<?php
session_start();
session_destroy();
session_start();
$_SESSION['message'] = "Vous êtes déconnectés.";
header('location: index.php');
?>
