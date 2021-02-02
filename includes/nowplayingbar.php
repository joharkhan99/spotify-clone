<?php
// some 10 random songs from database
$songQuery = mysqli_query($connection, "SELECT id FROM songs ORDER BY rand() LIMIT 10");
$resultArray = [];

while ($row = mysqli_fetch_assoc($songQuery)) {
  array_push($resultArray, $row['id']);
}

$jsonArray = json_encode($resultArray);   //to be accessed by javascript
?>

<script>
  $(document).ready(function() {

    // vars from script.js
    var newPlaylist = <?php echo $jsonArray ?>;
    audioElement = new Audio();
    setTrack(newPlaylist[0], newPlaylist, false);
    updateVolumeProgressBar(audioElement.audio);

    // stop highlighting the playbar at bottom
    $("#nowPlayingBarContainer").on("mousedown touchstart touchmove mousemove", function(e) {
      e.preventDefault();
    });

    // dragging bars/progress
    $(".playbackBar .progressBar").mousedown(function() {
      mouseDown = true; //var from script.js
    });

    $(".playbackBar .progressBar").mousemove(function(e) {
      if (mouseDown == true) {
        //set time of song depending on position of mouse
        timeFromOffset(e, this);
      }
    });

    $(".playbackBar .progressBar").mouseup(function(e) {
      //set time of song depending on position of mouse
      timeFromOffset(e, this);
    });

    //_____dragging VOLUME
    $(".volumeBar .progressBar").mousedown(function() {
      mouseDown = true; //var from script.js
    });

    $(".volumeBar .progressBar").mousemove(function(e) {
      if (mouseDown == true) {
        var percentage = e.offsetX / $(this).width();
        if (percentage >= 0 && percentage <= 1) //bcz volume is btw 1-0
          audioElement.audio.volume = percentage;
      }
    });

    $(".volumeBar .progressBar").mouseup(function(e) {
      var percentage = e.offsetX / $(this).width();
      if (percentage >= 0 && percentage <= 1) //bcz volume is btw 1-0
        audioElement.audio.volume = percentage;
    });
    //____dragging VOLUME END

    $(document).mouseup(function() {
      mouseDown = false;
    });

  });

  // time from offset/starting of song
  function timeFromOffset(mouse, progressBar) {
    var precentage = mouse.offsetX / $(progressBar).width() * 100;
    var seconds = audioElement.audio.duration * (precentage / 100);
    audioElement.setTime(seconds);
  }

  function prevSong() {
    //restart song if audio time is gretaer than 3 seconds
    if (audioElement.audio.currentTIme >= 3 || currentIndex == 0) {
      audioElement.setTime(0);
    } else {
      currentIndex = currentIndex - 1;
      setTrack(currentPlaylist[currentIndex], currentPlaylist, true);
    }
  }

  //will be used for playing next song
  function nextSong() {
    //if repeat is true/clicked then dont goto next song replay the same
    if (repeat == true) {
      audioElement.setTime(0);
      playSong();
      return;
    }

    if (currentIndex == currentPlaylist.length - 1) {
      currentIndex = 0;
    } else {
      currentIndex++;
    }

    //if shuffle true then get from shuffleplaylist or else from currplaylist
    var trackToPlay = shuffle ? shufflePlaylist[currentIndex] : currentPlaylist[currentIndex];
    setTrack(trackToPlay, currentPlaylist, true);
  }

  function setShuffle() {
    //if true set it to false and vice versa
    shuffle = !shuffle;
    $(".controlButton.shuffle i").toggleClass("active");

    if (shuffle == true) {
      //randomize playlist
      shuffleArray(shufflePlaylist);
      currentIndex = shufflePlaylist.indexOf(audioElement.currentlyPlaying.id);
    } else {
      //shuffle deactivateed -> go to regular playlist
      currentIndex = currentPlaylist.indexOf(audioElement.currentlyPlaying.id);
    }
  }

  function shuffleArray(arr) {
    for (let i = arr.length; i; i--) {
      var j = Math.floor(Math.random() * i);
      var x = arr[i - 1];
      arr[i - 1] = arr[i];
      arr[j] = x;
    }

  }

  function setRepeat() {
    if (repeat == true)
      repeat = false
    else
      repeat = true;

    $(".controlButton.repeat i").toggleClass("active");
  }

  function setMute() {
    //if true set it to false and vice versa
    audioElement.audio.muted = !audioElement.audio.muted;

    $(".controlButton.volume i").toggleClass("fas fa-volume-mute");
    $(".controlButton.volume i").toggleClass("far fa-volume-up");
  }

  function setTrack(trackId, newPlayList, play) {

    //if playlist is differnt
    if (newPlayList != currentPlaylist) {
      currentPlaylist = newPlayList;
      shufflePlaylist = currentPlaylist.slice();

      shuffleArray(shufflePlaylist);
    }

    if (shuffle == true) {
      currentIndex = shufflePlaylist.indexOf(trackId);
    } else {
      //to skip to next song (currIndx is index of played track in playlist)
      currentIndex = currentPlaylist.indexOf(trackId);
    }
    pauseSong();

    $.post("includes/handlers/ajax/getSongJson.php", {
        songId: trackId
      },
      function(response) {

        var track = JSON.parse(response);
        $('.trackName span').text(track.title);

        $.post("includes/handlers/ajax/getArtistJson.php", {
            artistId: track.artist
          },
          function(response) {
            var artist = JSON.parse(response);
            $('.trackInfo .artistName span').text(artist.name);
            $('.trackInfo .artistName span').attr('onclick', `openPage("artist.php?id=${artist.id}")`);
          });

        $.post("includes/handlers/ajax/getAlbumJson.php", {
            albumId: track.album
          },
          function(response) {
            var album = JSON.parse(response);
            $('.content .albumLink img').attr("src", album.artworkPath);
            $(".content .albumLink img").attr("onclick", "openPage('album.php?id=" + album.id + "')");
            $(".trackInfo .trackName span").attr("onclick", "openPage('album.php?id=" + album.id + "')");
          });

        audioElement.setTrack(track.path);

        // if play is true then play song
        if (play == true) {
          playSong();
        }

      });
  }

  function playSong() {

    if (audioElement.audio.currentTIme == 0) {
      $.post("includes/handlers/ajax/updatePlays.php", {
        songId: audioElement.currentlyPlaying.id
      });
    }

    $('.controlButton.play').hide();
    $('.controlButton.pause').show();
    audioElement.play();
  }

  function pauseSong() {
    $('.controlButton.play').show();
    $('.controlButton.pause').hide();
    audioElement.pause();
  }
</script>

<div id="nowPlayingBarContainer">
  <div id="nowplayingBar">

    <div id="nowPlayingLeft">
      <div class="content">
        <span class="albumLink">
          <img src="" alt="" class="albumArtWork" role="link" tabindex="0">
        </span>

        <div class="trackInfo">

          <span class="trackName">
            <span role="link" tabindex="0"></span>
          </span>

          <span class="artistName">
            <span role="link" tabindex="0"></span>
          </span>

        </div>

      </div>
    </div>

    <div id="nowPlayingCenter">
      <div class="content playerControls">
        <div class="buttons">

          <button class="controlButton shuffle" title="shuffle" onclick="setShuffle()">
            <i class="far fa-random"></i>
          </button>

          <button class="controlButton previous" title="previous" onclick="prevSong()">
            <i class="fas fa-step-backward"></i>
          </button>

          <button class="controlButton play" title="play" onclick="playSong()">
            <i class="fal fa-play-circle"></i>
          </button>

          <button class="controlButton pause" title="pause" style="display: none;" onclick="pauseSong()">
            <i class="fal fa-pause-circle"></i>
          </button>

          <button class="controlButton next" title="next" onclick="nextSong()">
            <i class="fas fa-step-forward"></i>
          </button>

          <button class="controlButton repeat" title="repeat" onclick="setRepeat()">
            <i class="fas fa-redo-alt"></i>
          </button>

        </div>

        <div class="playbackBar">
          <span class="progressTime current">0.00</span>

          <div class="progressBar">
            <div class="progressBarBg">
              <div class="progress"></div>
            </div>
          </div>

          <span class="progressTime remaining">0.00</span>
        </div>

      </div>
    </div>

    <div id="nowPlayingRight">
      <div class="volumeBar">
        <button class="controlButton volume" title="volume" onclick="setMute()">
          <i class="far fa-volume-up"></i>
        </button>

        <div class="progressBar">
          <div class="progressBarBg">
            <div class="progress"></div>
          </div>
        </div>

      </div>
    </div>

  </div>
</div>