<?php session_start();?>
<!DOCTYPE html>
<html>
<head>
  <title>PussyCam - Gallery</title>
  <meta charset="utf8">
  <meta name="viewport" content="width=device-width, user-scalable=no">
  <link rel="stylesheet" type="text/css" href="css/main.css">
  <link rel="stylesheet" type="text/css" href="css/gallery.css">
</head>
<body>
<div>
  <?php include "header.php"; ?>
  <section>
    <div>
      <a class="button" href="dashboard.php">Dashboard</a>
    </div>
    <?php
      // Error messages if picture show page id cannot be found
      if (isset($_GET["picture"]) && $_GET["picture"] == 'notfound') {
        echo "<br><br><div class=\"error\">Sorry, we can't seem to find this picture!</div>";
      }
    ?>
    <div id="thumbnails">
      <?php
        include "classes/Database.class.php";
        // for pagination, if not set, start on the first page
        if (!isset($_GET["from"]))
          $_GET["from"] = 0;
        $db = new DB($DB_DSN, $DB_USER, $DB_PASSWORD);

        // first check total count of images
        $sql = "SELECT COUNT(*) FROM pictures";
        $res = $db->find($sql, null);
        $total_count = $res[0]["COUNT(*)"];

        // then pagination
        $sql = "SELECT _id, base64 FROM pictures LIMIT 6 OFFSET ?"; //ORDER BY ? ID is already ordered...
        $pictures = $db->find($sql, [$_GET["from"]]);
        foreach ($pictures as $key => $picture) {
          echo "<div><a href=\"picture.php?_id=".$picture["_id"]."\"><img src=\"".$picture["base64"]."\"></a></div>";
        }
      ?>
    </div>
    <a class="button" href="gallery.php?from=<?=$_GET['from'] - 6 > 0 ? $_GET['from'] - 6 : 0?>" style="display:<?=$_GET['from'] == 0 ? 'none' : 'block'?>"><<<</a>
    <a class="button" href="gallery.php?from=<?=$_GET['from'] + 6 > $total_count - 1? $total_count - 1: $_GET['from'] + 6 ?>" style="display:<?=$_GET['from'] == $total_count - 1 ? 'none' : 'block'?>">>>></a>
  </section>
  <?php include "footer.php"; ?>
</div>
</body>
</html>