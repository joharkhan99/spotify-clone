<?php
ob_start();
session_start();
$timezone = date_default_timezone_set("Asia/Karachi");
$connection = mysqli_connect("localhost", "root", "", "spotify");

if (mysqli_connect_errno()) {
  die("DB Connection Failed: " . mysqli_errno($connection));
}
