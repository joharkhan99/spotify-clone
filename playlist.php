<?php include "includes/includedFile.php"; ?>

<?php
if (isset($_GET['id'])) {
  $playlistId = $_GET['id'];
} else {
  header("Location: index.php");
}

$playlist = new Playlist($connection, $playlistId);
$owner = new User($connection, $playlist->getOwner());

?>

<div class="entityInfo">

  <div class="leftSection">

    <div class="playlistImage">
      <img src="assets/img/playlist.jpg" alt="">
    </div>
  </div>

  <div class="rightSection">
    <h2><?php echo $playlist->getName(); ?></h2>
    <p>By <?php echo $playlist->getOwner() ?></p>
    <p><?php echo $playlist->getNumberOfSongs(); ?> Songs</p>
    <button class="button" onclick="deletePlayList(<?php echo $playlistId; ?>)">DELETE PLAYLIST</button>
  </div>

</div>

<div class="trackListContainer">
  <ul class="trackList">
    <?php
    $songIdArray = $playlist->getSongIds();

    $i = 1;

    foreach ($songIdArray as $songId) {

      $playlistSong = new Song($connection, $songId);
      $songArtist = $playlistSong->getArtist();

      echo "
        <li class='trackListRow'>
  
          <div class='trackCount'>
            <i class='fas fa-play play' onclick='setTrack(\"" . $playlistSong->getId() . "\",tempPlaylist,true)'></i>
            <span class='trackNumber'>$i</span>
          </div>

          <div class='trackInfo'>
            <span class='trackName'>" . $playlistSong->getTitle() . "</span>
            <span class='artistName'>" . $songArtist->getName() . "</span>
          </div>

          <div class='trackOptions'>
            <input type='hidden' class='songId' value='" . $playlistSong->getId() . "'>
            <i class='far fa-ellipsis-h' class='optionsButton' onclick='showOptionsMenu(this)'></i>
          </div>

          <div class='trackDuration'>
            <span class='duration'>" . $playlistSong->getDuration() . "</span>
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

<nav class="optionsMenu">

  <input type="hidden" class="songId">
  <?php echo Playlist::getPlaylistDropdown($connection, $userLoggedIn->getUsername()) ?>

  <div class="item" onclick="removeFromPlaylist(this,'<?php echo $playlistId; ?>')">Remove From Playlist</div>

</nav>


<?php include "includes/footer.php"; ?>