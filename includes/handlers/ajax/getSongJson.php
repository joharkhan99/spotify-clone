<?php
include "../../config.php";

if (isset($_POST['songId'])) {
  $songId = $_POST['songId'];

  $query  = mysqli_query($connection, "SELECT * FROM songs WHERE id=$songId");
  $resultArray = mysqli_fetch_assoc($query);

  echo json_encode($resultArray);     //to be used by javascript
}
