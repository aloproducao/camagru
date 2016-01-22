<?php session_start(); include "check_if_logged_in.php" ?>
<!DOCTYPE html>
<html>
<head>
  <title>PussyCam - Homepage</title>
  <meta charset="utf8">
  <meta name="viewport" content="width=device-width, user-scalable=no">
  <link rel="stylesheet" type="text/css" href="css/main.css">
</head>
<body>
<div>
  <?php include "header.php"; ?>
  <div id="banner" class="container index_banner">
    <section>
      <p><strong>Pussycam</strong> is a new tool that let's you take pictures and add that <strong>special someone</strong> to the mix.</p>
      <a class="button medium" href="login.php">Log In</a>
      <a class="button medium" href="signup.php">Sign Up</a>
    </section>
  </div>
  <?php include "footer.php"; ?>
</div>
</body>
</html>