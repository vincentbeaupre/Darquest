<?php require_once 'fonctions.php' ?>

<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>DarQuest</title>
  <link href="Assets/favicon.ico" rel="icon" type="image/x-icon" />
  <link rel="stylesheet" href="stylesheet.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <script src="fonctions.js"></script>
</head>

<body>
  <header>
    <div class="navbar">
      <div class="logo">
        <a href="index.php">DarQuest</a>
      </div>

      <div class="nav_links">
        <a href="market.php">Magasin</a>
        <a href="enigma.php">Enigma</a>
        <?php if (isset($_SESSION["alias"])) : ?>
          <a href="inventaire.php">Inventaire</a>
        <?php endif; ?>
      </div>

      <div class="shopping">
        <span class="soldeJoueur">
          <?php afficherMontant(Database::getSoldeJoueur($_SESSION['idJoueur'])) ?>
        </span>
        <a href="panier.php">
          <i class="fa fa-shopping-cart"></i>
          <?php
          $num_items = Database::getNumItemsInCart($_SESSION['idJoueur']);
          if ($num_items > 0) : ?>
            <span class='cart-badge'><?= $num_items ?></span>
          <?php endif; ?>
        </a>
      </div>

      <div class="user_links">
        <?php if (isset($_SESSION["alias"])) : ?>
          <a href="profil.php">
            <i class="fa fa-user-circle-o cart"></i>
            <span>&nbsp;<?= $_SESSION['alias'] ?></span>
          </a>
          <a href="logout.php">
            <i class="fa fa-sign-out icon"></i>
            <span>&nbsp;Déconnexion</span>
          </a>
        <?php else : ?>
          <a href="login.php">
            <i class="fa fa-sign-in icon"></i>
            <span>&nbsp;Connexion</span>
          </a>
        <?php endif; ?>
      </div>

      <div class="hamburger">
        <a href="javascript:void(0);" onclick="displayMenu()">
          <i class="fa fa-bars"></i>
        </a>
      </div>

    </div>
    <div class="menu">
      <a href="market.php">Magasin</a>
      <a href="enigma.php">Enigma</a>
      <?php if (isset($_SESSION["alias"])) : ?>
        <a href="inventaire.php">Inventaire</a>
        <a href="profil.php">
          <i class="fa fa-user-circle-o icon"></i>
          <span>&nbsp;<?= $_SESSION['alias'] ?></span>
        </a>
        <a href="logout.php">
          <i class="fa fa-sign-out icon"></i>
          <span>&nbsp;Déconnexion</span>
        </a>
      <?php else : ?>
        <a href="login.php">
          <i class="fa fa-sign-in icon"></i>
          <span>&nbsp;Connexion</span>
        </a>
      <?php endif; ?>
    </div>

  </header>