<?php session_start(); include "user_only.php" ?>
<!DOCTYPE html>
<html>
<head>
  <title>PussyCam - Dashboard</title>
  <meta charset="utf8">
  <meta name="viewport" content="width=device-width, user-scalable=no">
  <link rel="stylesheet" type="text/css" href="css/main.css">
  <link rel="stylesheet" type="text/css" href="css/dashboard.css"
</head>
<body>
<div class="main-container">
  <?php include "header.php"; ?>
  <section>
    <article class="left-container">
    <nav>
      <div class="menu_link">
        <a class="button" href="gallery.php">Gallery of Images</a>
      </div>
    </nav>
    <!-- checks if an image was just created -->
    <div class="info"><br><?php if (isset($_GET["image"]) && $_GET["image"] == "created") {echo "Image saved!";} ?></div>
    <!-- returns errors -->
    <div class="error"><br><?php
      if (isset($_GET["error"])) {
        switch ($_GET["error"]) {
        case "bad_extension":
          echo "Wrong Format. Please use jpg, jpeg or png";
          break;
        case "missing_clipart":
          echo "You have to select a filter!";
          break;
        case "undersized":
          echo "The uploded file is not the correct size. Please use an image that is at least 640 x 480";
          break;
        }
      }
    ?>
    </div>

    <div class="welcome">
      <h3>Welcome to PussyCam!</h3>
        <p>
          Take a picture with your webcam or upload a file.
        </p>
        <button id="webcam-button">Use my webcam</button>
        <button style="display:inline-block;margin-right:30px" id="upload-button">Upload a file</button>
      </h3>
    </div>
    <div class="tool-container">
      <div class="webcam">
        <br>
        <h3>Look at the camera</h3>
        <video></video>
        <canvas></canvas>
      </div>
      <div class="tool">
        <form method="POST" name="mixer" id="mixer" action="mixer.php" enctype="multipart/form-data">
          <input type="hidden" name="image_from_canvas" id="imageFromCanvas">
          <div class="upload">
            <h3> Pick an image </h3>
            <p>
              <span class="warning"> The picture must be at least 640x480. Only png, jpg and jpeg are accepted.</span>
            </p>
            <div class="form-control">
              <label>Choose an image</label>
              <input type="file" id="imageFromFile" name="image_from_file">
            </div>
          </div>
          <h3> Choose a filter </h3>
          <div class="form-control">
            <label>Save the image</label>
            <select required id="clipart" name="clipart">
              <option value="" selected>Choose an image</option>
              <optgroup label="cats">
                <option value="cat1.png">cat1.png</option>
                <option value="cat2.png">cat2.png</option>
                <option value="cat3.png">cat3.png</option>
                <option value="cat4.png">cat4.png</option>
                <option value="cat5.png">cat5.png</option>
                <option value="cat6.png">cat6.png</option>
                <option value="cat7.png">cat7.png</option>
                <option value="cat8.png">cat8.png</option>
              </optgroup>
            </select>
            <img id="preview">
          </div>
          <div id="superposition">
            <h3> Mix </h3>
            <div class="form-control">
              <input type="submit" value="Create new image!">
            </div>
          </div>
        </form>
      </div>
    </div>
    </article>
    <article class="thumbs-container">
      <h4 style="text-align:center">Your Images</h4>
      <ul>
        <?php
          include "classes/Database.class.php";
          $db = new DB($DB_DSN, $DB_USER, $DB_PASSWORD);
          $sql = "SELECT _id, base64 FROM pictures WHERE owner= ?"; // or only user?
          $pictures = $db->find($sql, [$_SESSION["user"]]);
          foreach ($pictures as $key => $picture) {
            echo "<li id=\"p".$picture["_id"]."\"><img class=\"thumbs\" src=\"".$picture["base64"]."\"><button class=\"suppress-picture ugly-button\" data-picture=\"".$picture["_id"]."\">Delete this!</button></li>";
          }
        ?>
      </ul>
    </article>
  </section>
  <div id="warning-low-res">
    <p>
      Your file is not wide enough! Please ensure it is at least 640px wide.
    </p>
  </div>
  <?php include "footer.php"; ?>
</div>
<script type="text/javascript" src="js/dashboard.js"></script>
</body>
</html>