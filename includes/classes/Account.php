<?php

class Account
{

  private $errorArray;
  private $connection;

  public function __construct($connection)
  {
    $this->errorArray = [];
    $this->connection = $connection;
  }

  public function login($un, $pw)
  {
    $query = mysqli_query($this->connection, "SELECT * FROM users WHERE username='$un' LIMIT 1");

    $password_verify = password_verify($pw, mysqli_fetch_assoc($query)['password']);

    if (mysqli_num_rows($query) == 1) {
      return true;
    } else {
      array_push($this->errorArray, Constants::$invalidCredentials);
      return;
    }
  }

  public function register($un, $fn, $ln, $em, $pw, $C_pw)
  {
    $this->validateUsername($un);
    $this->validateFirstName($fn);
    $this->validateLastName($ln);
    $this->validateEmail($em);
    $this->validatePassword($pw, $C_pw);

    if (empty($this->errorArray)) {   //means no errors in array
      //insert into DB
      return $this->insertUser($un, $fn, $ln, $em, $pw);
    } else {
      return false;
    }
  }

  private function insertUser($un, $fn, $ln, $em, $pw)
  {
    $encrypted_pass = password_hash($pw, PASSWORD_BCRYPT);
    $profilePic = "assets/images/profilePics/pinguin.jpg";
    $date = date("Y-m-d");

    $result = mysqli_query($this->connection, "INSERT INTO users VALUES('','$un','$fn','$ln','$em','$encrypted_pass','$date','$profilePic')");

    return $result;
  }

  public function getError($error)
  {
    if (!in_array($error, $this->errorArray)) {
      $error = "";
    }
    return "<span class='errorMessage'>$error</span>";
  }

  private function validateUsername($un)
  {
    if (strlen($un) > 25 || strlen($un) < 4) {
      array_push($this->errorArray, Constants::$usernameLength);
      return;
    }

    $checkUsername = mysqli_query($this->connection, "SELECT username FROM users WHERE username='$un'");
    if (mysqli_num_rows($checkUsername) != 0) {
      array_push($this->errorArray, Constants::$usernameTaken);
      return;
    }
  }

  private function validateFirstName($fn)
  {
    if (strlen($fn) > 25 || strlen($fn) < 2) {
      array_push($this->errorArray, Constants::$firstNamelength);
      return;
    }
  }

  private function validateLastName($ln)
  {
    if (strlen($ln) > 25 || strlen($ln) < 2) {
      array_push($this->errorArray, Constants::$lastNamelength);
      return;
    }
  }

  private function validateEmail($em)
  {
    if (!filter_var($em, FILTER_VALIDATE_EMAIL)) {    //checks email format
      array_push($this->errorArray, Constants::$emailFormatInvalid);
      return;
    }

    $checkEmail = mysqli_query($this->connection, "SELECT email FROM users WHERE email='$em'");
    if (mysqli_num_rows($checkEmail) != 0) {
      array_push($this->errorArray, Constants::$emailTaken);
      return;
    }
  }

  private function validatePassword($pw, $C_pw)
  {
    if ($pw != $C_pw) {
      array_push($this->errorArray, Constants::$passwordNotMatch);
      return;
    }
    if (preg_match('/[^A-Za-z0-9]/', $pw)) {    //if not in this range
      array_push($this->errorArray, Constants::$passwordNotAlhphaNumeric);
      return;
    }
    if (strlen($pw) > 30 || strlen($pw) < 8) {
      array_push($this->errorArray, Constants::$passwordLength);
      return;
    }
  }
}
