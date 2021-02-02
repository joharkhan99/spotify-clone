<?php
include "includes/includedFile.php";
?>

<div class="userDetails">

  <div class="container borderBottom">
    <h2>EMAIL</h2>
    <input type="text" class="email" name="email" placeholder="Email address..." value="<?php echo $userLoggedIn->getEmail(); ?>">
    <span class="message"></span>
    <button class="button" onclick="updateEmail('email')">SAVE</button>
  </div>

  <div class="container">
    <h2>PASSWORD</h2>
    <input type="password" class="oldPassword" name="oldPassword" placeholder="Current password">
    <input type="password" class="newPassword" name="newPassword" placeholder="New password">
    <input type="password" class="confirmPassword" name="confirmPassword" placeholder="Confirm password">
    <span class="message"></span>
    <button class="button" onclick="updatePassword('oldPassword','newPassword','confirmPassword')">SAVE</button>
  </div>

</div>