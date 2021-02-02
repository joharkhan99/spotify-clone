<?php
include "../../config.php";

if (!isset($_POST['username'])) {
  echo "ERROR: Could not set username";
  exit();
}

if (!isset($_POST['oldPassword']) || !isset($_POST['newPassword']) || !isset($_POST['confirmPassword'])) {
  echo "Not all Passwords have been set!";
}

if (empty($_POST['oldPassword']) || empty($_POST['newPassword']) || empty($_POST['confirmPassword'])) {
  echo "Please fill all fields";
  exit();
}

$username = $_POST['username'];
$oldPassword = $_POST['oldPassword'];
$newPassword = $_POST['newPassword'];
$confirmPassword = $_POST['confirmPassword'];

$getDbPass = mysqli_query($connection, "SELECT password FROM users WHERE username='$username'");

$dbPass = mysqli_fetch_assoc($getDbPass)['password'];
$verifypass = password_verify($oldPassword, $dbPass);

if (mysqli_num_rows($getDbPass) != 1 && !$verifypass) {
  echo "Password is incorrect";
  exit();
}

if ($newPassword != $confirmPassword) {
  echo "Your Passwords do not match";
  exit();
}

if (preg_match('/[^A-Za-z0-9]/', $newPassword)) {
  echo "Your password must contain only numbers and letters";
  exit();
}

if (strlen($newPassword) > 30 || strlen($newPassword) < 5) {
  echo "Password must be between 5 and 30 chars";
  exit();
}
//just testing git
$finalNewPass = password_hash($newPassword, PASSWORD_BCRYPT, ['cost' => 12]);

$query = mysqli_query($connection, "UPDATE users SET password='$finalNewPass' WHERE username='$username'");

echo "Password updated successfully";
