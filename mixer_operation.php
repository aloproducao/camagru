<?php
session_start();
include "user_only.php";
include "classes/Database.class.php";

if (isset($_POST["submit"]) && strlen($_POST["base64"]) > 0) {
  $db = new DB($DB_DSN, $DB_USER, $DB_PASSWORD);

  // sql request
  $sql = "INSERT INTO pictures (`_id`, `owner`, `base64`, `likes`, `comments`, `created_at`) VALUES('', ?, ?, ?, ?, '')";
  $user = [$_SESSION["user"], $_POST["base64"], serialize([]), serialize([])];
  $res = $db->insert($sql, $user);

  header("Location:dashboard.php?image=created");
}
?>