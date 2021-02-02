<?php
include "../../config.php";

if (!isset($_POST['username'])) {
  echo "ERROR: Could not set username";
  exit();
}

if (isset($_POST['email']) && !empty($_POST['email'])) {

  $username = $_POST['username'];
  $email = $_POST['email'];

  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "email is invalid";
  }

  $emailCheck = mysqli_query($connection, "SELECT email FROM users WHERE email='$email' AND username != '$username'");
  if (mysqli_num_rows($emailCheck) > 0) {
    echo "Email is already in use";
    exit();
  }

  $updateQuery = mysqli_query($connection, "UPDATE users SET email='$email' WHERE username='$username'");
  echo "Update Successfully!";
} else {
  echo "You must provide an email";
}
