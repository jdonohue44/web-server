<?php
include "../inc/dbinfo.inc";
session_start();
?>
<html>
<head>
  <link rel="stylesheet" type="text/css" href="./css/styles.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
</head>
<body>
<h1 align="center">Enter the email address you want to unsubscribe.</h1>
<?php
  /* Connect to MySQL and select the database. */
  $connection = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD);

  if (mysqli_connect_errno()) echo "Failed to connect to MySQL: " . mysqli_connect_error();

  $database = mysqli_select_db($connection, DB_DATABASE);

  $user_email = htmlentities($_POST['Email']);
?>

<div class="container">
<!-- Input form -->
<form action="<?PHP echo $_SERVER['SCRIPT_NAME'] ?>" method="POST">
        <label for="email_text">Email</label>
        <input type="text" name="Email" id="email_text" placeholder="Email" tabindex=2 maxlength="35" size="40" />

        <h5 id="validation_typing"></h5>

        <input type="submit" name="validate_button" value="Unsubscribe" />
</form>
</div>

<!-- Clean up. -->
<?php
  mysqli_free_result($result);
  mysqli_close($connection);
?>

<script>
  document.getElementsByName('validate_button')[0].disabled = true;

  // enable Validate button when Email is Valid
  $('#email_text').bind('input propertychange', function() {
    var text = $(this).val();
    $("#validation_typing").text("");
    var email = $('#email_text').val();
    if (validateEmail(email)) {
      $("#validation_typing").text(email + " is valid!");
      $("#validation_typing").css("color", "green");
      document.getElementsByName('validate_button')[0].disabled = false;
      document.getElementsByName('validate_button')[0].style.backgroundColor = "#4CAF50";
    } else {
      if(!email.length){
        document.getElementsByName('validate_button')[0].disabled = true;
        document.getElementsByName('validate_button')[0].style.backgroundColor = "#737373";
      }else{
        $("#validation_typing").text(email + " is not valid yet.");
        $("#validation_typing").css("color", "red");
        document.getElementsByName('validate_button')[0].disabled = true;
        document.getElementsByName('validate_button')[0].style.backgroundColor = "#737373";
      }
    }
  });

  function validateEmail(email) {
    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
  }

</script>
</body>
</html>

<?php

/* Add an employee to the table. */
function RemoveUser($connection, $email) {
   $e = mysqli_real_escape_string($connection, $email);
  //  $check_query = sprintf("SELECT * FROM `USERS` (`Name`,`Email`) WHERE `Email` = '%s';",
  //  mysqli_real_escape_string($e));
     $query = "DELETE FROM USERS WHERE Email = $e;";
     mysqli_query($connection, $query);
}
?>