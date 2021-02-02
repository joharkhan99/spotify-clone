<div id="navBarContainer">
  <nav class="navBar">
    <span class="logo" role="link" tabindex="0" onclick="openPage('index.php')">
      <i class="fab fa-napster"></i>
    </span>

    <div class="group">
      <div class="navItem">
        <span tabindex="0" role="link" onclick="openPage('search.php')" class="navItemLink">Search <i class="far fa-search icon"></i></span>
      </div>
    </div>

    <div class="group">
      <div class="navItem">
        <span tabindex="0" role="link" onclick="openPage('browse.php')" class="navItemLink">Browse</span>
      </div>
      <div class="navItem">
        <span tabindex="0" role="link" onclick="openPage('yourMusic.php')" class="navItemLink">Your Music</span>
      </div>
      <div class="navItem">
        <span tabindex="0" role="link" onclick="openPage('settings.php')" class="navItemLink">
          <p><?php echo $userLoggedIn->getFirstAndLastName(); ?></p>
        </span>
      </div>
    </div>

  </nav>
</div>