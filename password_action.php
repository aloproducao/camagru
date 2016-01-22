<?php
session_start();
include('classes/Database.class.php');
include('classes/Mailer.class.php');

$fields = ["p1", "p2", "token"];
$errors = [];

// error checking
foreach ($fields as $key => $field) {
  if (!isset($_POST[$field]) || strlen($_POST[$field]) == 0) {
    $errors[$field] = "missing";
    $_POST[$field] = "";
  }
}

if (!isset($errors["p1"]) && !preg_match("/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$/", $_POST["p1"])) {
  $errors["p1"] = "format";
}

if ($_POST["p1"] != $_POST["p2"]) {
 $errors["p1"] = $errors["p2"] = "mismatch";
}

// building errored url
$url = "password.php";
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
  header("Location: ".$url."&token=".$_POST["token"]);
}
else {
  // real stuff begins
  $db = new DB($DB_DSN, $DB_USER, $DB_PASSWORD);

  $sql = "SELECT _id, username FROM users WHERE token=?";
  $args = [$_POST["token"]];
  $user = $db->find($sql, $args);
  if (count($user) == 0) { // oh no!
    header("Location: password.php?account=notfound&token=".$_POST["token"]);
  }
  else {
    $hash = hash("whirlpool", $_POST["p1"]);
    $sql = "UPDATE users SET password = ?, token = ? WHERE _id = ?";
    $data = $db->update($sql, [$hash, null, $user[0]["_id"]]);
    header("Location: password_changed.php");
  }
}
?>