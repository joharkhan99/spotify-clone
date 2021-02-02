<?php include "includes/includedFile.php"; ?>

<?php
if (isset($_GET['id'])) {
  $albumId = $_GET['id'];

  $albumQuery = mysqli_query($connection, "SELECT * FROM albums WHERE id='$albumId'");
  $album = mysqli_fetch_assoc($albumQuery);

  $artistId = $album['artist'];   //artist id is present in album table

  $album = new Album($connection, $albumId);
  $artist = $album->getArtist();
}
?>

<div class="entityInfo">

  <div class="leftSection">
    <img src="<?php echo $album->getArtWorkPath(); ?>" alt="">
  </div>

  <div class="rightSection">
    <h2><?php echo $album->getTitle(); ?></h2>
    <p>By <?php echo $artist->getName(); ?></p>
    <p><?php echo $album->getNumberOfSongs() ?> Songs</p>
  </div>

</div>

<div class="trackListContainer">
  <ul class="trackList">
    <?php
    $songIdArray = $album->getSongIds();

    $i = 1;

    foreach ($songIdArray as $songId) {

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

<nav class="optionsMenu">

  <input type="hidden" class="songId">
  <?php echo Playlist::getPlaylistDropdown($connection, $userLoggedIn->getUsername()) ?>

</nav>

<?php include "includes/footer.php"; ?>