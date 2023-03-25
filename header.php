<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>DarQuest</title>
  <link rel="stylesheet" href="stylesheet.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <script src="fonctions.js"></script>
</head>

<body>
  <header>
    <nav>
      <div class="logo">
        <a href="index.php">DarQuest</a>
      </div>
      <div class="nav_links">
        <a href="market.php">Magasin</a>
        <a href="enigma.php">Enigma</a>
        <?php
        if (isset($_SESSION["alias"])) {
          echo '<a href="inventaire.php">Inventaire</a>';
        }
        ?>
      </div>
      <div class="user_info">
        <?php
        if (isset($_SESSION["alias"])) {
          echo "<a href='profil.php'>
                <i class='fa fa-user-circle-o icon'></i>
                <span>&nbsp" . $_SESSION['alias'] . "</span>
              </a>
              <a href='logout.php'>
                <i class='fa fa-sign-out icon'></i>
                <span>&nbspDéconnexion</span>

              </a>";
        } else {
          echo "<a href='login.php'>
                <i class='fa fa-sign-in icon'></i>
                <span>&nbspConnexion</span>
              </a>";
        }
        ?>
      </div>
    </nav>
  </header>