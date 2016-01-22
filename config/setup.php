<?php
include('database.php');

try {
  echo "Start - ";
	$dbh = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
	// drop tables if exist
  echo " - Dropping Tables - ";
	$dbh->query("DROP TABLE users");
	$dbh->query("DROP TABLE pictures");
	// here create tables (with correct columns)
  echo " - Creating Tables";
  $dbh->query("CREATE TABLE users (_id INT(16) PRIMARY KEY NOT NULL AUTO_INCREMENT, username VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, token VARCHAR(255) NOT NULL, verified BOOLEAN NOT NULL)");
  $dbh->query("CREATE TABLE pictures (_id INT(16) PRIMARY KEY NOT NULL AUTO_INCREMENT, owner VARCHAR(255) NOT NULL, base64 LONGBLOB NOT NULL, likes TEXT NOT NULL, comments TEXT NOT NULL, created_at datetime NOT NULL DEFAULT NOW())");
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}
?>
