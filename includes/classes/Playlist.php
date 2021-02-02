<?php

class Playlist
{
  private $connection;
  private $id;
  private $name;
  private $owner;

  public function __construct($con, $data)
  {
    if (!is_array($data)) {
      // data is an id string then do a query
      $query = mysqli_query($con, "SELECT * FROM playlists WHERE id=$data");
      $data = mysqli_fetch_assoc($query);
    }

    $this->connection = $con;
    $this->id = $data['id'];
    $this->name = $data['name'];
    $this->owner = $data['owner'];
  }

  public function getName()
  {
    return $this->name;
  }

  public function getId()
  {
    return $this->id;
  }

  public function getOwner()
  {
    return $this->owner;
  }

  public function getNumberOfSongs()
  {
    $query = mysqli_query($this->connection, "SELECT songid FROM playlistsongs WHERE playlistId=$this->id");

    return mysqli_num_rows($query);
  }

  public function getSongIds()
  {
    $query = mysqli_query($this->connection, "SELECT songId FROM playlistsongs WHERE playlistId='$this->id' ORDER BY playlistOrder ASC");

    $songIdsArray = [];
    while ($row = mysqli_fetch_assoc($query)) {
      $songIdsArray[] = $row['songId'];
    }
    return $songIdsArray;
  }

  public static function getPlaylistDropdown($con, $username)
  {
    $dropdown = '<select class="item playlist" id="">
                  <option value="">Add to playlist</option>
                ';
    $query = mysqli_query($con, "SELECT id, name FROM playlists WHERE owner='$username'");
    while ($row = mysqli_fetch_assoc($query)) {

      $id = $row['id'];
      $name = $row['name'];

      $dropdown .= "<option value='$id'>$name</option>";
    }

    return $dropdown . "</select>";
  }
}
