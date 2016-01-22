<?php
session_start();
include('classes/Database.class.php');
include('classes/Mailer.class.php');
?>
<!DOCTYPE html>
<html>
<head>
  <title>PussyCam - Password Change</title>
  <meta charset="utf8">
  <meta name="viewport" content="width=device-width, user-scalable=no">
  <link rel="stylesheet" type="text/css" href="css/main.css">
</head>
<body>
<div>
<?php include "header.php"; ?>
  <form method="POST" action="password_action.php">
    <fieldset>
      <?php
      if (isset($_GET["token"])) {
        echo "<input type=\"hidden\" name=\"token\" value=\"".$_GET["token"]."\">";
      } else {
        echo "<span class=\"error\">You need the password token.</span>";
      }
      ?>
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
                echo "<span class=\"error\">A password must contain one uppercase letter, one lowercase letter and one digit";
                break;
              case "mismatch":
                echo "<span class=\"error\">The passwords must match</span>";
            }
          }
        ?>
      </div>
      <div class="form-control">
        <label>Password Confirmation</label>
        <input type="password" name="p2" required>
      </div>
      <div class="form-control">
        <input type="submit" value="submit">
      </div>
    </fieldset>
  </form>
<?php include "footer.php"; ?>
</div>
</body>
</html>