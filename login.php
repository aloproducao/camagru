<?php session_start(); include "check_if_logged_in.php" ?>
<!DOCTYPE html>
<html>
<head>
  <title>PussyCam - Login</title>
  <meta charset="utf8">
  <meta name="viewport" content="width=device-width, user-scalable=no">
  <link rel="stylesheet" type="text/css" href="css/main.css">
</head>
<body>
<div>
  <?php include "header.php"; ?>
  <section>
    <form method="POST" action="login_operation.php">
      <fieldset>
        <div class="form-control">
          <label>Username</label>
          <input type="text" name="username" required>
          <!-- errors -->
          <?php
            if (isset($_GET["username"])) {
              switch ($_GET["username"]) {
                case "missing":
                  echo "<span class=\"error\">The username field is empty. Please fill it out.</span>";
                  break;
                case "notverified":
                  echo "<span class=\"error\">This account exists but is not yet verified. Check your email and click the verification link</span>";
                  break;
                case "notfound":
                  echo "<span class=\"error\">This username does not exist</span>";
                  break;
              }
            }
          ?>
        </div>
        <div class="form-control">
          <label>Password</label>
          <input type="password" name="password" required>
          <?php
            if (isset($_GET["password"])) {
              switch ($_GET["password"]) {
                case "missing":
                  echo "<span class=\"error\">Please enter a password</span>";
                  break;
                case "mismatch":
                  echo "<span class=\"error\">The password entered is incorrect</span>";
              }
            }
          ?>
        </div>
        <div class="form-control">
          <input type="submit" value="submit">
        </div>
      </fieldset>
    </form>
    <form method="POST" action="forgotten_action.php">
      <fieldset>
        <h3>Forgotten your password?</h3>
        <div class="form-control">
          <label>email</label>
          <input type="email" name="email">
          <?php
            if (isset($_GET["email"])) {
              switch ($_GET["email"]) {
                case "missing":
                  echo "<span class=\"error\">Please give us your email!</span>";
                  break;
                case "format":
                  echo "<span class=\"error\">We do not have any record of this email in our database</span>";
                  break;
              }
            }
          ?>
        </div>
        <div class="form-control">
          <input type="submit" name="submit" value="Send me a new password">
        </div>
      </fieldset>
    </form>
  </section>
  <?php include "footer.php"; ?>
</div>
</body>
</html>