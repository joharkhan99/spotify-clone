var currentPlaylist = [];
var shufflePlaylist = [];
var tempPlaylist = [];
var audioElement;
var mouseDown = false;
var currentIndex = 0;
var repeat = false;
var shuffle = false;
var userLoggedIn;
var timer;

function formatTime(seconds) {
  var time = Math.round(seconds);
  var minutes = Math.floor(time / 60);
  var seconds = time - minutes * 60;

  return minutes + ":" + (seconds >= 10 ? seconds : "0" + seconds);
}

$(document).on('change', 'select.playlist', function () {
  var select = $(this);
  var playlistId = select.val();
  var songId = select.prev('.songId').val();

  $.post("includes/handlers/ajax/addToPlaylist.php", { playlistId: playlistId, songId: songId })
    .done(function (error) {

      if (error != "") {
        alert(error);
        return;
      }
      hideOptionsMenu();
      select.val("");      //also remove selected opton from select tag
    });

});

function removeFromPlaylist(button, playlistId) {
  var songId = $(button).prevAll(".songId").val();

  $.post("includes/handlers/ajax/removeFromPlaylist.php", { playlistId: playlistId, songId: songId })
    .done(function (error) {
      // do something when ajax returns

      if (error != "") {
        alert(error);
        return;
      }

      openPage('playlist.php?id=' + playlistId);
    });

}

function updateTimeProgressBar(audio) {
  // here time increases
  $('.progressTime.current').text(formatTime(audio.currentTime));
  // here time decreases
  $('.progressTime.remaining').text(formatTime(audio.duration - audio.currentTime));

  var progress = audio.currentTime / audio.duration * 100;  //calc percent
  $(".playbackBar .progress").css('width', progress + "%");
}

function updateVolumeProgressBar(audio) {
  var volume = audio.volume * 100;  //calc volume percent
  $(".volumeBar .progress").css('width', volume + "%");
}

function playFirstSong() {
  setTrack(tempPlaylist[0], tempPlaylist, true);
}

function createPlaylist() {
  var popup = prompt("Please enter naame of playlist");

  if (popup != "") {
    $.post("includes/handlers/ajax/createPlaylist.php", { name: popup, username: userLoggedIn })
      .done(function (error) {
        // do something when ajax returns

        if (error != "") {
          alert(error);
          return;
        }

        openPage('yourMusic.php');
      });
  }
}

function deletePlayList(playlistId) {
  var prmpt = confirm("Are U Sure u wanna delete this playlist?");

  if (prmpt == true) {

    $.post("includes/handlers/ajax/deletePlaylist.php", { playlistId: playlistId })
      .done(function (error) {
        // do something when ajax returns

        if (error != "") {
          alert(error);
          return;
        }

        openPage('yourMusic.php');
      });
  }
}

// $(document).click(function (click) {
//   var target = $(click.target);

//   if (!target.hasClass("item") && !target.hasClass("optionsButton")) {
//     hideOptionsMenu();
//   }
// });

$(window).scroll(function () {
  hideOptionsMenu();
});

function showOptionsMenu(button) {
  var songId = $(button).prevAll(".songId").val();
  var menu = $(".optionsMenu");
  var menuWidth = menu.width();

  menu.find(".songId").val(songId);

  //distance from top of window to top of doc
  var scrollTop = $(window).scrollTop();
  //get distance of button from top of document
  var elementOffset = $(button).offset().top;

  var top = elementOffset - scrollTop;
  var left = $(button).position().left;

  menu.css({ "top": top + "px", "left": left - menuWidth + "px", "display": "inline" });

}

function hideOptionsMenu() {
  var menu = $(".optionsMenu");

  if (menu.css("display") != "none") {
    menu.css("display", "none");
  }
}

// Audio Class
function Audio() {
  this.currentlyPlaying;
  this.audio = document.createElement("audio");

  //when song ends call next song
  this.audio.addEventListener('ended', function () {
    nextSong();
  });

  this.audio.addEventListener('canplay', function () {
    // 'this' refers to the obj that the event was called on
    var duration = formatTime(this.duration);
    $('.progressTime.remaining').text(duration);
  });

  //calls everytime time changes
  this.audio.addEventListener('timeupdate', function () {
    if (this.duration) {
      updateTimeProgressBar(this);
    }
  });

  // for volume progress bar
  this.audio.addEventListener('volumechange', function () {
    updateVolumeProgressBar(this);
  });

  this.setTrack = function (track) {
    this.currentlyPlaying = track;
    this.audio.src = track;
  }

  this.play = function () {
    var audio = this.audio;
    setTimeout(function () {
      audio.play();
    }, 150);
  }

  this.pause = function () {
    var audio = this.audio;
    setTimeout(function () {
      audio.pause();
    }, 150);
  }

  this.setTime = function (seconds) {
    this.audio.currentTime = seconds;
  }
}

function openPage(url) {
  if (timer != null) {
    clearTimeout(timer);
  }

  //means url doesnt have ? sign then add ? to url
  if (url.indexOf("?") == -1) {
    url = url + "?";
  }

  //encodeURI() lets u pass text to url and also changes some text like %20
  //also UserLoggedIn contains username and will be used on diff places
  var encodedUrl = encodeURI(url + "&userLoggedIn=" + userLoggedIn);
  $("#mainContent").load(encodedUrl);

  $("body").scrollTop(0);
  history.pushState(null, null, url);   //for showing url in address bar
}

function logout() {
  $.post("includes/handlers/ajax/logout.php",
    function (response) {
      location.reload();
    });
}

function updateEmail(emailClass) {
  var emailValue = $("." + emailClass).val();

  $.post("includes/handlers/ajax/updateEmail.php", { email: emailValue, username: userLoggedIn })
    .done(function (response) {

      $("." + emailClass).nextAll(".message").text(response);

    });
}

function updatePassword(oldPasswordClass, newPasswordClass, newConfirmPasswordClass) {

  var oldPass = $("." + oldPasswordClass).val();
  var newPass = $("." + newPasswordClass).val();
  var newConfirmPass = $("." + newConfirmPasswordClass).val();

  $.post("includes/handlers/ajax/updatePassword.php", {
    username: userLoggedIn,
    oldPassword: oldPass,
    newPassword: newPass,
    confirmPassword: newConfirmPass
  })
    .done(function (response) {

      $("." + oldPasswordClass).nextAll(".message").text(response);

    });
}