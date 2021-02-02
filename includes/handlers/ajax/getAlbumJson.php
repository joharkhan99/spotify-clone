<?php
include "../../config.php";

if (isset($_POST['albumId'])) {
  $albumId = $_POST['albumId'];

  $query  = mysqli_query($connection, "SELECT * FROM albums WHERE id=$albumId");
  $resultArray = mysqli_fetch_assoc($query);

  echo json_encode($resultArray);     //to be used by javascript
}
