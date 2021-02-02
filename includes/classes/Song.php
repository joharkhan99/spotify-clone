<?php

class Song
{

  private $id;
  private $connection;
  private $mysqli_data;
  private $title;
  private $artistId;
  private $albumId;
  private $genre;
  private $duration;
  private $path;

  public function __construct($connection, $id)
  {
    $this->id = $id;
    $this->connection = $connection;

    $query = mysqli_query($this->connection, "SELECT * FROM songs WHERE id='$this->id'");

    $this->mysqli_data = mysqli_fetch_assoc($query);
    $this->title = $this->mysqli_data['title'];
    $this->artistId = $this->mysqli_data['artist'];
    $this->albumId = $this->mysqli_data['album'];
    $this->genre = $this->mysqli_data['genre'];
    $this->path = $this->mysqli_data['path'];
    $this->duration = $this->mysqli_data['duration'];
  }

  public function getTitle()
  {
    return $this->title;
  }

  public function getId()
  {
    return $this->id;
  }

  public function getArtist()
  {
    return new Artist($this->connection, $this->id);
  }

  public function getAlbum()
  {
    return new Album($this->connection, $this->albumId);
  }

  public function getPath()
  {
    return $this->path;
  }

  public function getDuration()
  {
    return $this->duration;
  }

  public function getMysqliData()
  {
    return $this->mysqli_data;
  }

  public function getGenre()
  {
    return $this->genre;
  }
}
