<?php
session_start();
include('classes/Database.class.php');
include('classes/Mailer.class.php');

$fields = ["username", "password"];
$errors = [];

// 0) Check if the required fields are all present
foreach ($fields as $key => $field) {
  if (!isset($_POST[$field]) || strlen($_POST[$field]) == 0) {
    $errors[$field] = "missing";
    $_POST[$field] = "";
  }
}

$url = "login.php";
$i = 0;
foreach ($errors as $field => $error) {
  if ($i == 0) {
    $url .= "?";
  }
  $url .= $field."=".$error;
  if ($i < count($errors) - 1) {
    $url .= "&";
  }
  $i++;
}

if (count($errors)) {
  header("Location: ".$url);
}

// else check for the account by username, hash the provided password and compare it to the one saved in db
else {
  $db = new DB($DB_DSN, $DB_USER, $DB_PASSWORD);
  $hash = hash("whirlpool", $_POST["password"]);

  // sql request
  $sql = "SELECT username, password, verified FROM users WHERE username = ?";
  $user = [$_POST['username']];
  $user = $db->find($sql, $user);

  if (count($user) == 0) {
    header("Location: login.php?username=notfound");
  }
  else {
    if ($user[0]["password"] != $hash) {
      header("Location: login.php?password=mismatch");
    }
    elseif ($user[0]["verified"] == 0) {
      header("Location: login.php?username=notverified");
    }
    else {
      $_SESSION["user"] = $user[0]["username"];
      header("Location: dashboard.php");
    }
  }
}
?>