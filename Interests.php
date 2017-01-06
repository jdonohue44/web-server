<?php include "../inc/dbinfo.inc"; ?>
<html>
<head>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <link rel="stylesheet" type="text/css" href="./css/styles.css">
</head>
<body>
  <?php
    /* Connect to MySQL and select the database. */
    $connection = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD);
    if (mysqli_connect_errno()) echo "Failed to connect to MySQL: " . mysqli_connect_error();
    $database = mysqli_select_db($connection, DB_DATABASE);
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
      <tr>
        <td>
        <input type="submit" value="Submit" />
        </td>
      </tr>
    </table>
  </form>

  <ul id="interests_ul">
  </ul>

  <script>
    $('#add_interest_button').click(function(){
      $('ul').append('<li>'+$('#interest_text').val()+'<button type="button" id="delete_btn">X</button></li>');
      $('#interest_text').val('')
      });
  // document.getElementById('interest_text').disabled = true;
    $("#delete_btn").click(function(){
      console.log("delete");
    });
  </script>
</body>
</html>
