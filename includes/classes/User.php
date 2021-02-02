<?php

class User
{
  private $connection;
  private $username;

  public function __construct($con, $username)
  {
    $this->connection = $con;
    $this->username = $username;
  }

  public function getUsername()
  {
    return $this->username;
  }

  public function getEmail()
  {
    $query = mysqli_query($this->connection, "SELECT email FROM users WHERE username='$this->username'");
    $row = mysqli_fetch_array($query);
    return $row['email'];
  }

  public function getFirstAndLastName()
  {
    // concat func adds strings
    $query = mysqli_query($this->connection, "SELECT concat(firstName, ' ', lastName) as 'name'  FROM users WHERE username='$this->username'");
    $row = mysqli_fetch_array($query);
    return $row['name'];
  }
}
