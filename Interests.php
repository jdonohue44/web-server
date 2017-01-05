<?php include "../inc/dbinfo.inc"; ?>
<html>
<head>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
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

  <script>
    $('#add_interest_button').click(function(){
      $("table").find('tbody')
        .append($('<tr>')
          .append($('<td>')
              .text('#interest_text');
          )
        );
    });
  // document.getElementById('interest_text').disabled = true;
  </script>
</body>
</html>
