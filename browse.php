<?php include "includes/includedFile.php"; ?>


<h1 class="pageHeadingBig">You Might Also Like</h1>

<div class="gridViewContainer">
  <?php

  $query = mysqli_query($connection, "SELECT * FROM albums ORDER BY rand() LIMIT 10");
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