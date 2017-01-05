<?php include "../inc/dbinfo.inc"; ?>
<html>
<head>
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
          <button type="button" value="Add"/>
        </td>
      </tr>
      <tr>
        <input type="submit" value="submit" />
      </tr>
    </table>
  </form>

  <script>
  // document.getElementById('add_interest_button').disabled = true;
  // document.getElementById('interest_text').disabled = true;
  </script>
</body>
</html>
