<?php
session_start();
include "user_only.php";
include "classes/Database.class.php";
include('classes/Mailer.class.php');

$db = new DB($DB_DSN, $DB_USER, $DB_PASSWORD);

if (isset($_POST["comment_button"])) {
  $sql = "SELECT * FROM pictures WHERE _id = ?"; // or only user?
  $pictures = $db->find($sql, [$_POST["picture_id"]]);
  $picture = $pictures[0];
  $comments = unserialize($picture["comments"]);
  $comment = $_SESSION["user"].", ".date("M d Y - h:i a").": ".htmlentities($_POST["comment"], ENT_QUOTES, "UTF-8");
  $comments[] = $comment;

  $comments = serialize($comments);
  $sql = "UPDATE pictures SET comments = ? WHERE _id = ?";
  $data = $db->update($sql, [$comments, $_POST["picture_id"]]);

  $sql = "SELECT username, email FROM users WHERE username = ?"; // or only user?
  $user = $db->find($sql, [$picture["owner"]]);
  $mailer = new Mailer();
  $mailer->send(
    $user[0]["email"],
    "There is a new comment on your picture!",
    "<p>Hello ".$user[0]["username"].", someone has just commented on one of your pictures.</p><quote>".$comment."</quote> Have a look <a href=\"http://localhost:8080/picture.php?_id=".$picture["_id"]."\">here</a>!"
  );
  header("Location:picture.php?_id=".$_POST["picture_id"]);
}
elseif (isset($_POST["like"])) {
  $sql = "SELECT * FROM pictures WHERE _id = ?"; // or only user?
  $pictures = $db->find($sql, [$_POST["picture_id"]]);
  $picture = $pictures[0];
  $likes = unserialize($picture["likes"]);
  $found = false;
  foreach ($likes as $key => $like) {
    if ($like == $_SESSION["user"])
      $found = true;
  }
  if (!$found) {
    $likes[] = $_SESSION["user"];
  }

  $likes = serialize($likes);
  $sql = "UPDATE pictures SET likes = ? WHERE _id = ?";
  $data = $db->update($sql, [$likes, $_POST["picture_id"]]);
  header("Location:picture.php?_id=".$_POST["picture_id"]);
}
?>