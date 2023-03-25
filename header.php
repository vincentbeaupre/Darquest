<head>
  <title>DarQuest</title>
  <link rel="stylesheet" href="stylesheet.css">
  <script src="fonctionJavaScript.js"></script>
  <script src="https://kit.fontawesome.com/93bf009440.js" crossorigin="anonymous"></script>
  <?php require 'fonction.php'; ?>
</head>

<body>
  <header>
    <nav class="nav-container">
      <div class="logo">
        <a href="index.php">DarQuest</a>
      </div>
      <ul class="nav-links">
        <li><a href="market.php">Marché</a></li>
        <li><a href="quest.php">Enigma</a></li>
        <?php
        if (isset($_SESSION["alias"])) {
          echo '<li><a href="inventaire.php">Inventaire</a></li>';
        }
        ?>
      </ul>
      <ul class="nav-links right">
        <li><a href="login.php"><i class="fa-regular fa-circle-user userIcon"></i><?php echo isset($_SESSION["alias"]) ? $_SESSION["alias"] : ""; ?></a></li>
      </ul>

    </nav>
  </header>