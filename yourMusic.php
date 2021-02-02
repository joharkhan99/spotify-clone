<?php include "includes/includedFile.php"; ?>

<div class="playlistsContainer">
  <div class="gridViewContainer">
    <h2>PLAYLISTS</h2>

    <div class="buttonItems">
      <button class="button green" onclick="createPlaylist()">NEW PLAYLISTS</button>
    </div>

    <?php

    $username = $userLoggedIn->getUsername();

    $playListQuery = mysqli_query($connection, "SELECT * FROM playlists WHERE owner='$username'");

    if (mysqli_num_rows($playListQuery) == 0) {
      echo "<span class='noResults'>You don't have any playlists.</span>";
    }

    while ($row = mysqli_fetch_assoc($playListQuery)) {

      $playlist = new Playlist($connection, $row);

      echo "<div class='gridViewItem' role='link' tabIndex='0' onclick='openPage(\"playlist.php?id=" . $playlist->getId() . "\")'>

            <div class='playlistimage'>
              <img src='assets/img/playlist.jpg' alt=''>
            </div>

              <div class='gridViewInfo'>
              " . $playlist->getName() . "
              </div>

            </div>
        ";
    }
    ?>

  </div>
</div>