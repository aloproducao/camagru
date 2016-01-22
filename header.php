<header>
  <div class="logo">
    <a href="index.php"><img class="logo-picture" src="photos/logo.jpg"></a>
  </div>
  <span class="title">PussyCam</span>
  <div class="action-items">
    <div class="items">
      <?php
        if (isset($_SESSION["user"])) {
          echo "<span>Hi there, ".$_SESSION['user']." <a href='logout_action.php'>Logout</a></span>";
        }
        else {
          echo "<div><a href=\"login.php\">Login</a></div> <div><a href=\"signup.php\">Sign Up</a></div>";
        }
      ?>
    </div>
  </div>
</header>