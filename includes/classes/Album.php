<?php

class Album
{

  private $id;
  private $connection;
  private $title;
  private $artistId;
  private $genre;
  private $artWorkPath;

  public function __construct($connection, $id)
  {
    $this->id = $id;
    $this->connection = $connection;

    $albumQuery = mysqli_query($this->connection, "SELECT * FROM albums WHERE id='$this->id'");
    $album = mysqli_fetch_assoc($albumQuery);

    $this->title = $album['title'];
    $this->artistId = $album['artist'];
    $this->genre = $album['genre'];
    $this->artWorkPath = $album['artworkPath'];
  }

  public function getTitle()
  {
    return $this->title;
  }

  public function getArtWorkPath()
  {
    return $this->artWorkPath;
  }

  public function getArtist()
  {
    return new Artist($this->connection, $this->artistId);
  }

  public function getGenre()
  {
    return $this->genre;
  }
  public function getNumberOfSongs()
  {
    return mysqli_num_rows(mysqli_query($this->connection, "SELECT id FROM songs WHERE album='$this->id'"));
  }
  public function getSongIds()
  {
    $query = mysqli_query($this->connection, "SELECT id FROM songs WHERE album='$this->id' ORDER BY albumOrder ASC");

    $songIdsArray = [];
    while ($row = mysqli_fetch_assoc($query)) {
      $songIdsArray[] = $row['id'];
    }
    return $songIdsArray;
  }
}
