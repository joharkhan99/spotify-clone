<?php

function sanitizeFormPassword($text)
{
  $text = strip_tags($text);    //removes html elems from input if there
  return $text;
}

function sanitizeFormUsername($text)
{
  $text = strip_tags($text);    //removes html elems from input if there
  $text = str_replace(" ", "", $text);    //johar khan -> joharkhan  
  return $text;
}

function sanitizeFormString($text)
{
  $text = strip_tags($text);
  $text = str_replace(" ", "", $text);
  $text = ucfirst(strtolower($text)); // johar -> Johar
  return $text;
}

// register
if (isset($_POST['registerButton'])) {
  $username = sanitizeFormUsername($_POST['username']);

  $firstname = sanitizeFormString($_POST['firstname']);
  $lastname = sanitizeFormString($_POST['lastname']);
  $email = sanitizeFormString($_POST['email']);
  $password = sanitizeFormPassword($_POST['password']);
  $password_confirm = sanitizeFormPassword($_POST['confirm-password']);

  $wasSuccessful = $account->register($username, $firstname, $lastname, $email, $password, $password_confirm);

  if ($wasSuccessful) {
    $_SESSION['username'] = $username;
    header("Location: index.php");
  }
}
