<?php
include "../../config.php";

if (isset($_POST['playlistId']) && isset($_POST['songId'])) {

  $playlistid = $_POST['playlistId'];
  $songid = $_POST['songId'];

  $orderQuery = mysqli_query($connection, "SELECT MAX(playlistOrder)+1 AS playlistOrder FROM playlistsongs WHERE playlistId=$playlistid");

  $row = mysqli_fetch_assoc($orderQuery);
  $order = $row['playlistOrder'];

  $query = mysqli_query($connection, "INSERT INTO playlistsongs VALUES('','$songid','$playlistid','$order')");
} else {
  echo "PlaylistId or SongId was not passed into addToPlaylist.php";
}
