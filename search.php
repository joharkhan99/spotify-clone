<?php
include "includes/includedFile.php";

if (isset($_GET['term'])) {
  $term = urldecode($_GET['term']);
} else {
  $term = "";
}
?>

<div class="searchContainer">
  <h4>Search for an artist, album or song</h4>
  <input type="text" class="searchInput" value="<?php echo $term; ?>" placeholder="Start typing..." onfocus="this.value = this.value">
</div>

<script>
  //give focus to input on reload
  $(".searchInput").focus();

  //below will wait for 1 sec then will get val from input and reload page
  //user will dont know shit about the reload
  $(function() {

    $(".searchInput").keyup(function() {
      clearTimeout(timer);

      timer = setTimeout(function() {
        var val = $(".searchInput").val();
        openPage("search.php?term=" + val);
      }, 1000);

    });

  });
</script>

<div class="trackListContainer borderBottom">
  <h2>SONGS</h2>
  <ul class="trackList">
    <?php

    if (isset($_GET['term']) && !empty($_GET['term'])) {

      $songsQuery = mysqli_query($connection, "SELECT id FROM songs WHERE title LIKE '$term%' ORDER BY title");

      if (mysqli_num_rows($songsQuery) == 0) {
        echo "<span class='noResults'>No Songs Found For " . $term . "</span>";
      }

      $songIdArray = [];

      $i = 1;

      while ($row = mysqli_fetch_assoc($songsQuery)) {

        //bcz we wanna show only 15 on search
        if ($i > 15) {
          break;
        }

        array_push($songIdArray, $row['id']);

        $albumSong = new Song($connection, $row['id']);
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
    }
    ?>

    <script>
      var tempSongIds = '<?php echo json_encode($songIdArray); ?>';
      tempPlaylist = JSON.parse(tempSongIds);
    </script>

  </ul>
</div>

<div class="artistContainer borderBottom">
  <h2>ARTISTS</h2>

  <?php

  if (isset($_GET['term']) && !empty($_GET['term'])) {

    $artistsQuery = mysqli_query($connection, "SELECT id FROM artists WHERE name LIKE '$term%' ORDER BY name");

    if (mysqli_num_rows($artistsQuery) == 0) {
      echo "<span class='noResults'>No Artists Found For " . $term . "</span>";
    }

    while ($row = mysqli_fetch_assoc($artistsQuery)) {
      $artistFOund = new Artist($connection, $row['id']);


      echo ' <div class="searchResultRow">
            <div class="artistName">
              <span class="link" tabIndex="0" onclick="openPage(\'artist.php?id=' . $artistFOund->getId() . '\')">
              ' .
        $artistFOund->getName()
        . '
              </span>
            </div>
          </div>
    
    ';
    }
  }
  ?>

</div>


<div class="gridViewContainer">
  <h2>ALBUMS</h2>
  <?php

  if (isset($_GET['term']) && !empty($_GET['term'])) {

    $albumQuery = mysqli_query($connection, "SELECT * FROM albums WHERE title LIKE '$term%' ORDER BY title");

    if (mysqli_num_rows($albumQuery) == 0) {
      echo "<span class='noResults'>No Albums Found For " . $term . "</span>";
    }

    while ($row = mysqli_fetch_assoc($albumQuery)) {

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
  }
  ?>
</div>

<nav class="optionsMenu">

  <input type="hidden" class="songId">
  <?php echo Playlist::getPlaylistDropdown($connection, $userLoggedIn->getUsername()) ?>

</nav>