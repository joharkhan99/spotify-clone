<?php
include "includes/config.php";
include "includes/classes/Account.php";
include "includes/classes/Constants.php";

$account = new Account($connection);

include "includes/handlers/registerHandler.php";
include "includes/handlers/loginHandler.php";

function getInputValue($name)
{
  echo isset($_POST[$name]) ? $_POST[$name] : "";
}
?>

<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SPOOT</title>
  <link rel="stylesheet" href="assets/css/register.css?v=<?php echo time(); ?>">

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>



  <script src="assets/js/register.js"></script>

</head>

<body>

  <?php
  if (isset($_POST['registerButton']))
    echo "  
      <script>
        $(document).ready(function() {
          // hiding and showing login/signup forms on load
          $('#registerForm').show();
          $('#loginForm').hide();
          // hiding and showing login/signup forms on load end
        });
      </script>";
  else
    echo "  
      <script>
        $(document).ready(function() {
          // hiding and showing login/signup forms on load
          $('#registerForm').hide();
          $('#loginForm').show();
          // hiding and showing login/signup forms on load end
        });
      </script>";
  ?>

  <div id="background">

    <div id="loginContainer">

      <!-- leftside section -->
      <div id="inputContainer">

        <!-- login form -->
        <form action="register.php" id="loginForm" method="POST">
          <h2>Login to your account</h2>
          <p>
            <label for="loginUsername">Username</label>
            <input type="text" id="loginUsername" name="loginUsername" placeholder="e.g. elonMusk" required value="<?php getInputValue("loginUsername"); ?>">
          </p>
          <p>
            <label for="loginPassword">Password</label>
            <input type="password" id="loginPassword" name="loginPassword" placeholder="Enter Password" required value="<?php getInputValue("loginPassword"); ?>">
          </p>

          <button type="submit" name="loginButton">Log In</button>
          <div class="hasAccount">
            <span id="hideLogin">Don't have an account? Signup</span>
          </div>
        </form>


        <!-- register form -->
        <form action="register.php" id="registerForm" method="POST">
          <h2>Create Your Account</h2>
          <p>
            <?php echo $account->getError(Constants::$usernameLength); ?>
            <?php echo $account->getError(Constants::$usernameTaken); ?>
            <label for="username">Username</label>
            <input type="text" id="username" name="username" placeholder="e.g. elonMusk" required value="<?php getInputValue("username"); ?>">
          </p>
          <p>
            <?php echo $account->getError(Constants::$firstNamelength); ?>
            <label for="firstname">Firstname</label>
            <input type="text" id="firstname" name="firstname" placeholder="e.g. elon" required value="<?php getInputValue("firstname"); ?>">
          </p>
          <p>
            <?php echo $account->getError(Constants::$lastNamelength); ?>
            <label for="lastname">Lastname</label>
            <input type="text" id="lastname" name="lastname" placeholder="e.g. Musk" required value="<?php getInputValue("lastname"); ?>">
          </p>
          <p>
            <?php echo $account->getError(Constants::$emailFormatInvalid); ?>
            <?php echo $account->getError(Constants::$emailTaken); ?>
            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="e.g. elon@example.com" required value="<?php getInputValue("email"); ?>">
          </p>
          <p>
            <?php echo $account->getError(Constants::$passwordNotMatch); ?>
            <?php echo $account->getError(Constants::$passwordNotAlhphaNumeric); ?>
            <?php echo $account->getError(Constants::$passwordLength); ?>
            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Enter Password" required value="<?php getInputValue("password"); ?>">
          </p>
          <p>
            <label for="confirm-password">Confirm Password</label>
            <input type="password" id="confirm-password" name="confirm-password" placeholder="Enter password again" required value="<?php getInputValue("confirm-password"); ?>">
          </p>

          <button type="submit" name="registerButton">Sign Up</button>
          <div class="hasAccount">
            <span id="hideRegister">Already have an account? Login</span>
          </div>
        </form>

      </div>
      <!-- leftside section end -->

      <!-- rightside section -->
      <div id="loginText">
        <h1>Get great music, right now</h1>
        <h2>Listen to loads of songs for free</h2>
        <ul>
          <li><span class="check">&#x2714;</span>Discover music you'll fall in love with</li>
          <li><span class="check">&#x2714;</span>Create your own playlists</li>
          <li><span class="check">&#x2714;</span>Follow artists to keep up to date</li>
        </ul>
      </div>
      <!-- rightside section end -->

    </div>
  </div>

</body>

</html>