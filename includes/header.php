<?php
include "includes/config.php";
include "includes/classes/User.php";
include "includes/classes/Artist.php";
include "includes/classes/Album.php";
include "includes/classes/Song.php";
include "includes/classes/Playlist.php";

if ($_SESSION['username']) {
  $userLoggedIn = new User($connection, $_SESSION['username']);
  $username = $userLoggedIn->getUsername();
  echo "<script>userLoggedIn='$username'</script>";
} else {
  header("Location: register.php");
}

?>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SPOOT</title>
  <link rel="stylesheet" href="assets/css/style.css?v=<?php echo time(); ?>">

  <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

  <script src="assets/js/script.js?v=<?php echo time(); ?>"></script>

</head>

<body>

  <script>
    var audioElement = new Audio();
    audioElement.setTrack('assets/music/bensound-acousticbreeze.mp3');

    document.body.addEventListener("click", function() {
      audioElement.audio.play();
    });
  </script>

  <div id="mainContainer">

    <div id="topContainer">
      <!-- navbar -->
      <?php include "includes/navbarcontainer.php" ?>

      <!-- middle area -->
      <div class="mainViewContainer">
        <div id="mainContent">