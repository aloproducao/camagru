<?php
session_start();
include('classes/Database.class.php');
include('classes/Mailer.class.php');
?>
<!DOCTYPE html>
<html>
<head>
  <title>PussyCam</title>
  <meta charset="utf8">
  <meta name="viewport" content="width=device-width, user-scalable=no">
  <link rel="stylesheet" type="text/css" href="css/main.css">
</head>
<body>
<div>
  <?php include "header.php"; ?>
    <?php

    $error = false;
    $db = new DB($DB_DSN, $DB_USER, $DB_PASSWORD);

    if (!isset($_GET["token"]) || strlen($_GET["token"]) != 128) {
      $error = true;
    }
    else {
      // sql request
      $sql = "SELECT _id, username FROM users WHERE token=?";
      $args = [$_GET["token"]];
      $user = $db->find($sql, $args);
      if (count($user) == 0) {
        $error = true;
      }
    }

    if ($error)
      echo "Invalid token. Please click the link emailed to you or ask for another confirmation link.";
    else {
      $sql = "UPDATE users SET verified = ?, token = ? WHERE _id = ?";
      $data = $db->update($sql, [1, null, $user[0]["_id"]]);
      echo "Account confirmed. Please <a href=\"http://localhost:8080/login.php\">login</a>";
    }
    ?>

  <?php include "footer.php"; ?>
</div>
</body>
</html>