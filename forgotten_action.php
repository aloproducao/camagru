<?php
session_start();
include('classes/Database.class.php');
include('classes/Mailer.class.php');

if (!isset($_POST["email"]) || $_POST["email"] === "") {
  header("Location: login.php?email=missing");
}
elseif (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
  header("Location: login.php?email=format");
}
else {
  $db = new DB($DB_DSN, $DB_USER, $DB_PASSWORD);
  $token = bin2hex(openssl_random_pseudo_bytes(64));

  $sql = "UPDATE users SET token = ? WHERE email = ?";
  $data = $db->update($sql, [$token, $_POST["email"]]);
  $mailer = new Mailer();
  $mailer->send(
    $_POST["email"],
    "Changing your password",
    "To change your password please click <strong><a href=\"http://localhost:8080/password.php?token=".$token."\">here.</a></strong>"
  );
  header("Location: password_email_verification.php");
}
?>