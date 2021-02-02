<?php
include "../../config.php";

if (isset($_POST['playlistId']) && isset($_POST['songId'])) {

  $playlistid = $_POST['playlistId'];
  $songid = $_POST['songId'];

  $query = mysqli_query($connection, "DELETE FROM playlistsongs WHERE playlistId=$playlistid AND songId=$songid");
} else {
  echo "PlaylistId or songId was not passed into removefromPlaylist.php";
}
