<?php

class Artist
{

  private $id;
  private $connection;

  public function __construct($connection, $id)
  {
    $this->id = $id;
    $this->connection = $connection;
  }

  public function getId()
  {
    return $this->id;
  }

  public function getName()
  {
    $artistQuery = mysqli_query($this->connection, "SELECT name FROM artists WHERE id='$this->id'");
    $artist = mysqli_fetch_assoc($artistQuery);

    return $artist['name'];
  }
  public function getSongIds()
  {
    $query = mysqli_query($this->connection, "SELECT id FROM songs WHERE artist='$this->id' ORDER BY plays ASC");

    $songIdsArray = [];
    while ($row = mysqli_fetch_assoc($query)) {
      $songIdsArray[] = $row['id'];
    }
    return $songIdsArray;
  }
}
