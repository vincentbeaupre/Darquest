<nav class="header">
  <div class="menu-button">
    <input type="checkbox" class="toggle">
    <div class="hamburger">
      <div></div>
    </div>
    <div class="menu">
      <div>
        <div>
          <ul>
            <li><a href="market.html">Marché</a></li>
            <li><a href="quest.php">Enigma</a></li>
            <?php
            if (isset($_SESSION["alias"])) {
              echo '<li><a href="inventaire.php">Inventaire</a></li>';
            }
            ?>
          </ul>
        </div>
      </div>
    </div>
  </div>
</nav>

<!-- <a class="navTitle" href="http://167.114.152.54/~darquest6/">
    <h2>DarQuest</h2>
  </a>
  <a class="navItem" href="http://167.114.152.54/~darquest6/market.php">
    <p>Market</p>
  </a>
  <a class="navItem" href="http://167.114.152.54/~darquest6/inventaire.php">
    <p>Inventaire</p>
  </a>
  <a class="navItem" href="http://167.114.152.54/~darquest6/quest.php">
    <p>Quest</p>
  </a>
  <svg class="navItem" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-menu">
    <line x1="3" y1="12" x2="21" y2="12"></line>
    <line x1="3" y1="6" x2="21" y2="6"></line>
    <line x1="3" y1="18" x2="21" y2="18"></line>
  </svg> -->