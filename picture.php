<?php
session_start();

// if there is no id (i.e users manipulate the url), return to gallery
if (!isset($_GET["_id"]))
  header("Location: gallery.php");
else {
  include "classes/Database.class.php";
  $db = new DB($DB_DSN, $DB_USER, $DB_PASSWORD);
  $sql = "SELECT * FROM pictures WHERE _id = ?"; // or only user?
  $pictures = $db->find($sql, [$_GET["_id"]]);
  // if there is no result with this id (return to gallery with error message)
  if (count($pictures) == 0)
    header("Location: gallery.php?picture=notfound");
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>PussyCam - Picture Page</title>
  <meta charset="utf8">
  <meta name="viewport" content="width=device-width, user-scalable=no">
  <link rel="stylesheet" type="text/css" href="css/main.css">
</head>
<body>
<div>
  <?php include "header.php"; ?>
  <section>
    <nav>
      <div>
        <a class="button" href="gallery.php">Gallery</a>
      </div>
      <div>
        <a class="button" href="dashboard.php">Dashboard</a>
      </div>
    </nav>
    <!-- IMAGE -->
      <?php
        $picture = $pictures[0];
        // image
        echo "<img class=\"picture\" src=\"".$picture["base64"]."\">";
      ?>

    <!-- ACTIONS  -->
    <?php
    if (!isset($_SESSION["user"])) {
      echo "<p style=\"margin-left:37px\">You must be logged in to like or comment. Please <a href=\"login.php\">login</a> or <a href=\"signup.html\">signup.</a></p>";
    }
    ?>
    <form method="POST" action="picture_action.php" style="<?php if (!isset($_SESSION["user"])) {echo "display:none";}?>"
      <fieldset>
        <input type="hidden" name="picture_id" value="<?= $_GET["_id"] ?>">
        <div class="form-control">

          <!-- Likes -->
          <?php
            $liked = false;
            $likes = unserialize($picture["likes"]);
            $likes_nb = count($likes);
            if (isset($_SESSION["user"])) {
              foreach ($likes as $key => $like) {
                if ($_SESSION["user"] == $like) {
                  $liked = true;
                  break;
                }
              }
            }
          ?>
          <div>
            <?php
            if ($liked) {
              echo "<font color=\"#a849a3\">You like this picture!</font>";
            } else {
              echo "<input type=\"submit\" name=\"like\" value=\"Like\">";
            }
            ?>
            <br />
            <?php
            if ($likes_nb == 1) {
              echo "<font color=\"#a849a3\">" . $likes_nb . " like</font>";
            } else {
              echo "<font color=\"#a849a3\">" . $likes_nb . " likes</font>";
            }
            ?>
          </div>
        </div>

        <!-- COMMENTS -->
        <ul>
        <?php
          $comments = unserialize($picture["comments"]);
          foreach ($comments as $key => $comment) {
            echo "<li>".$comment."</li>";
          }
        ?>
        </ul>

        <div>
          <div class="form-control">
            <textarea name="comment" placeholder="All comments are public."></textarea>
          </div>
          <div class="form-control">
            <input type="submit" name="comment_button" value="Submit comment">
          </div>
        </div>
      </fieldset>
    </form>
  </section>
  <?php include "footer.php"; ?>
</div>
</body>
</html>