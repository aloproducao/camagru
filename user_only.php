<?php
  if (!isset($_SESSION["user"])) {
    header("Location: error_unauthorized.php");
  }
?>