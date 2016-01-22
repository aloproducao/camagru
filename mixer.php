<?php session_start();
include "user_only.php";

  if (!isset($_POST["clipart"])) {
    header("Location:dashboard.php?error=missing_clipart");
  }
  $filename = $_POST["clipart"];
  $original = false;

  // gets image from file
  if (isset($_FILES["image_from_file"]) && $_FILES["image_from_file"]["size"] > 0) {
    // creating the final image (blank for now)
    $original = imagecreatetruecolor(640, 480);
    if ($_FILES["image_from_file"]["error"] == 0) {
      $tmp_name = $_FILES["image_from_file"]["tmp_name"];

      // checking if uploaded image respect the size minima
      $size = getimagesize($tmp_name);
      $width = $size[0];
      $height = $size[1];
      if ($width < 640 || $height < 480) {
        header("Location:dashboard.php?error=undersized");
      }

      // checking if uploaded image is from the correct format ; if so, create a temp image
      switch($_FILES["image_from_file"]["type"]) {
        case 'image/png':
            $temp = imagecreatefrompng($tmp_name);
            break;
        case 'image/jpg':
            $temp = imagecreatefromjpeg($tmp_name);
            break;
        case 'image/jpeg':
            $temp = imagecreatefromjpeg($tmp_name);
            break;
        default:
            header("Location:dashboard.php?error=bad_extension");
      }

      // crop the temp image
      imagecopyresized($original, $temp, 0, 0, 0, 0, 640, 480, 640, 480);
    }
  }

  // gets image from webcam (i.e. canvas)
  elseif (isset($_POST["image_from_canvas"])) {
    // print_r($_POST["image_from_canvas"]);
    $data = base64_decode($_POST['image_from_canvas']);
    $original = imagecreatefromstring($data);
  }

  if ($original != false) {
    // get info about chosen image
    $size = getimagesize("photos/".$filename);
    $width = $size[0];
    $height = $size[1];
    $clipart = imagecreatefrompng('photos/'.$filename);

    //allows the transparency of the clipart over the image
    imagealphablending($original, true);
    imagesavealpha($original, true);
    // superimposes clipart onto the original
    imagecopy($original, $clipart, 0, 0, 0, 0, $width, $height);

    // writing new image to temp file
    imagepng($original, "photos/temp/temp.png");

    // get data from the file and encode it to base64 string
    $data = file_get_contents("photos/temp/temp.png");
    $base64 = 'data:image/png;base64,' . base64_encode($data);
    ?>

<!-- HTML START -->
<!DOCTYPE html>
<html>
<head>
  <title>PussyCam - Filtered Image</title>
  <meta charset="utf8">
  <meta name="viewport" content="width=device-width, user-scalable=no">
  <link rel="stylesheet" type="text/css" href="css/main.css">
</head>
<body>
<div>
  <?php include "header.php"; ?>
    <p>
      Voilà!
    </p>

    <!-- Puts the image on the screen -->
      <?php
      echo "<img src=\"".$base64."\">";

      // destroys temp file and variables
      unlink("photos/temp/temp.png");
      imagedestroy($original);
      imagedestroy($clipart);
    }
    ?>
    <form method="POST" action="mixer_operation.php">
      <?php echo "<input type=\"hidden\" name=\"base64\" value=\"".$base64."\">" ?>
      <div class="form-control" style="display:inline-block">
        <a style="height:41px; line-height:41px; width:270px" href="dashboard.php">Delete Image</a>
      </div>
      <div class="form-control" style="display:inline-block;margin-left:370px">
        <input type="submit" name="submit" value="Save Image" style="cursor:pointer">
      </div>
    </form>

  <?php include "footer.php"; ?>
</div>
</body>
</html>
