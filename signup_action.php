<?php
session_start();
include('classes/Database.class.php');
include('classes/Mailer.class.php');

$fields = ["username", "email", "p1", "p2"];
$errors = [];

// 0) Check if the required fields are all present
foreach ($fields as $key => $field) {
  if (!isset($_POST[$field]) || strlen($_POST[$field]) == 0) {
    $errors[$field] = "missing";
    $_POST[$field] = "";
  }
}

// 1) Check if the username is not oversized
if (!isset($errors["username"])) {
  if (strlen($_POST["username"]) > 50) {
    $errors["username"] = "oversized";
  }
  elseif (strpos($_POST["username"], "<") || strpos($_POST["username"], ">")) {
    $errors["username"] = "html";
  }
}

// 2) Check if the email is correctly formatted and not oversized
if (!isset($errors["email"])) {
  if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
    $errors["email"] = "format";
  }
  elseif (strlen($_POST["email"] > 254)) {
    $errors["email"] = "oversized";
  }
  elseif (strpos($_POST["email"], "<") || strpos($_POST["email"], ">")) {
    $errors["email"] = "html";
  }
}

// 3) Check if the providedpassword is secured a minima
if (!isset($errors["p1"]) && !preg_match("/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$/", $_POST["p1"])) {
  $errors["p1"] = "format";
}

// 4) Check if the two passwords ar ok
if ($_POST["p1"] != $_POST["p2"]) {
 $errors["p1"] = $errors["p2"] = "mismatch";
}

// 5) building custom url based on found errors
$url = "signup.php";
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

// if there is at least one error, redirect to signup page with appropriate GET fields
if (count($errors)) {
  header("Location: ".$url);
}
// else, hash the password, save the new user in db then send confirmation email
else {
  $db = new DB($DB_DSN, $DB_USER, $DB_PASSWORD);
  $hash = hash("whirlpool", $_POST["p1"]);
  $token = bin2hex(openssl_random_pseudo_bytes(64));

  // sql request
  $sql = "INSERT INTO users (`_id`, `username`, `email`, `password`, `token`, `verified`) VALUES('', ?, ?, ?, ?, ?)";
  $user = [htmlentities($_POST["username"], ENT_QUOTES, "UTF-8"), $_POST["email"], $hash, $token, 0];
  $res = $db->insert($sql, $user);

  // username and email are unique fields – if there are dupes, return error
  if (isset($res["error"]) && $res["error"] == "dupes") {
     header("Location: signup.php?".$res["field"]."=dupes");
  } else {
    $mailer = new Mailer();
    $mailer->send(
      $_POST["email"],
      "Welcome to PussyCam, the place where you can make your pictures look a little less lonely!",
      "To verify your account, please click <strong><a href=\"http://localhost:8080/validate.php?token=".$token."\">the link.</a></strong>"
    );
    header("Location: verification.php");
  }
}
?>