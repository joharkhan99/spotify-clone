<?php
include "../../config.php";

if (isset($_POST['artistId'])) {
  $artistId = $_POST['artistId'];

  $query  = mysqli_query($connection, "SELECT * FROM artists WHERE id=$artistId");
  $resultArray = mysqli_fetch_assoc($query);

  echo json_encode($resultArray);     //to be used by javascript
}
