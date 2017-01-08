<?php
include "../inc/dbinfo.inc";
session_start();
?>
<html>
<head>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <link rel="stylesheet" type="text/css" href="./css/styles.css">
</head>
<body>
  <?php
    $name  = $_SESSION["name"];
    $email = $_SESSION["email"];
    /* Connect to MySQL and select the database. */
    $connection = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD);
    if (mysqli_connect_errno()) echo "Failed to connect to MySQL: " . mysqli_connect_error();
    $database = mysqli_select_db($connection, DB_DATABASE);

    $user_interests = $_POST['interests'];
    for($i = 0; $i < sizeof($user_interests); $i++){
      echo $user_interests[$i] . "<br />";
    }
    //updateInterestsTable($name, $email);
    ?>
  <h2>Interests</h2>
  <form action="<?PHP echo $_SERVER['SCRIPT_NAME'] ?>" method="POST">
    <table border="0">
      <tr>
        <td>Interest</td>
      </tr>
      <tr>
        <td>
          <input id="interest_text" type="text" name="Interest" maxlength="45" size="30" />
        </td>
        <td>
          <button type="button" id="add_interest_button">Add</button>
        </td>
      </tr>
    </table>

  <ul id="interests_ul">
  </ul>
  <input type="submit" value="Submit" />
  </form>

  <?php
  function updateInterestsTable(){
    $query = "INSERT INTO `USERS` (`Name`,`Email`) VALUES ('$n', '$e');";
    if(!mysqli_query($connection, $query)) echo("<p>Error adding employee data.</p>");
  }
  ?>

  <script>
    $('#add_interest_button').click(function(){
      var interest = $('#interest_text').val();
      $('ul').append("<li>"+interest+"<input type='hidden' name='interests[]' value='"
      +interest+"'/></li>");
      $('#interest_text').val('');
    });
  </script>

</body>
</html>
