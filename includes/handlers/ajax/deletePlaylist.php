<?php
include "../../config.php";

if (isset($_POST['playlistId'])) {

  $playlistid = $_POST['playlistId'];

  $playlistQuery = mysqli_query($connection, "DELETE FROM playlists WHERE id=$playlistid");

  $songsQuery = mysqli_query($connection, "DELETE FROM playlistsongs WHERE playlistId=$playlistid");
} else {
  echo "PlaylistId was not passed into deletePlaylist.php";
}
