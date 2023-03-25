<?php
  session_start();

  if (!isset($_SESSION['alias'])) {
    header('location: index.php');
  }
?>
<p>Page de profil de <?php echo $_SESSION['alias'];?> </p>