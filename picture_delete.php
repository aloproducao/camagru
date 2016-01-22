<?php
session_start();
include "user_only.php";
include "classes/Database.class.php";

if (isset($_POST["picture_id"])) {
  $db = new DB($DB_DSN, $DB_USER, $DB_PASSWORD);

  $sql = "DELETE FROM pictures WHERE _id = ? AND owner = ?";
  $data = $db->update($sql, [$_POST["picture_id"], $_SESSION["user"]]);
  echo $data;
}
?>