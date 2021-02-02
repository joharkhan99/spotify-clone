<?php
include "includes/includedFile.php";

if (isset($_GET['id'])) {
  $artistId = $_GET['id'];
} else {
  header("Location: index.php");
}

$artist = new Artist($connection, $artistId);
?>

<div class="entityInfo borderBottom">
  <div class="centerSection">

    <div class="artistInfo">
      <h1 class="artistName"><?php echo $artist->getName(); ?></h1>

      <div class="headerButtons">
        <button class="button green" onclick="playFirstSong()">Play</button>
      </div>

    </div>

  </div>
</div>

<!-- SOng sect -->
<div class="trackListContainer borderBottom">
  <h2>SONGS</h2>
  <ul class="trackList">
    <?php
    $songIdArray = $artist->getSongIds();

    $i = 1;

    foreach ($songIdArray as $songId) {

      //bcz we wanna show only 5 on page load
      if ($i > 5) {
        break;
      }

      $albumSong = new Song($connection, $songId);
      $albumArtist = $albumSong->getArtist();

      echo "
        <li class='trackListRow'>
  
          <div class='trackCount'>
            <i class='fas fa-play play' onclick='setTrack(\"" . $albumSong->getId() . "\",tempPlaylist,true)'></i>
            <span class='trackNumber'>$i</span>
          </div>

          <div class='trackInfo'>
            <span class='trackName'>" . $albumSong->getTitle() . "</span>
            <span class='artistName'>" . $albumArtist->getName() . "</span>
          </div>

          <div class='trackOptions'>
            <input type='hidden' class='songId' value='" . $albumSong->getId() . "'>
            <i class='far fa-ellipsis-h' class='optionsButton' onclick='showOptionsMenu(this)'></i>
          </div>

          <div class='trackDuration'>
            <span class='duration'>" . $albumSong->getDuration() . "</span>
          </div>

        </li>";

      $i++;
    }

    ?>

    <script>
      var tempSongIds = '<?php echo json_encode($songIdArray); ?>';
      tempPlaylist = JSON.parse(tempSongIds);
    </script>

  </ul>
</div>


<div class="gridViewContainer">
  <h2>ALBUMS</h2>
  <?php

  $query = mysqli_query($connection, "SELECT * FROM albums WHERE artist=$artistId");
  while ($row = mysqli_fetch_assoc($query)) {

    echo "<div class='gridViewItem'>
  
            <a href='album.php?id=" . $row['id'] . "'>
              <img src='" . $row['artworkPath'] . "' alt=''>

              <div class='gridViewInfo'>
              " . $row['title'] . "
              </div>
            </a>

          </div>
          ";
  }

  ?>
</div>

<nav class="optionsMenu">

  <input type="hidden" class="songId">
  <?php echo Playlist::getPlaylistDropdown($connection, $userLoggedIn->getUsername()) ?>

</nav>