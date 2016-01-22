<?php session_start(); include "check_if_logged_in.php" ?>
<!DOCTYPE html>
<html>
<head>
  <title>PussyCam - Setup Your Account</title>
  <meta charset="utf8">
  <meta name="viewport" content="width=device-width, user-scalable=no">
  <link rel="stylesheet" type="text/css" href="css/main.css">
</head>
<body>
<div>
  <?php include "header.php"; ?>
  <section>
    <form method="POST" action="signup_action.php">
      <fieldset>
        <div class="form-control">
          <label>Username</label>
          <input type="text" name="username" required>
          <?php
            if (isset($_GET["username"])) {
              switch ($_GET["username"]) {
                case "missing":
                  echo "<span class=\"error\">Please fill out the username field</span>";
                  break;
                case "dupes":
                  echo "<span class=\"error\">Username already exists</span>";
                  break;
                case "html":
                  echo "<span class=\"error\">Invalid characters in your username</span>";
                  break;
                case "oversized":
                  echo "<span class=\"error\">Username must be less than 50 characters</span>";
                  break;
              }
            }
          ?>
        </div>
        <div class="form-control">
          <label>Email</label>
          <input type="email" name="email" required>
          <?php
            if (isset($_GET["email"])) {
              switch ($_GET["email"]) {
                case "missing":
                  echo "<span class=\"error\">Please fill out the email field</span>";
                  break;
                case "dupes":
                  echo "<span class=\"error\">This email is already associated with an existing account</span>";
                  break;
                case "oversized":
                  echo "<span class=\"error\">Email must be less than 250 characters</span>";
                  break;
                case "html":
                  echo "<span class=\"error\">Invalid characters in your email</span>";
                  break;
                case "format":
                  echo "<span class=\"error\">Email format is not correct</span>";
                  break;
              }
            }
          ?>
        </div>
        <div class="form-control">
          <label>Password</label>
          <input type="password" name="p1" required>
          <?php
            if (isset($_GET["p1"])) {
              switch ($_GET["p1"]) {
                case "missing":
                  echo "<span class=\"error\">Please fill out the password field</span>";
                  break;
                case "format":
                  echo "<span class=\"error\">Your password must contain one uppercase letter, one lowercase letter and one digit.";
                  break;
                case "mismatch":
                  echo "<span class=\"error\">Passwords do not match</span>";
              }
            }
          ?>
        </div>
        <div class="form-control">
          <label>Password Confirmation</label>
          <input type="password" name="p2" required>
        </div>
        <br>
        <div class="form-control">
          <input type="submit" value="submit">
        </div>
      </fieldset>
    </form>
  </section>
  <?php include "footer.php"; ?>
</div>
</body>
</html>