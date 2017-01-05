<?php include "../inc/dbinfo.inc"; ?>
<html>
<head>
  <link rel="stylesheet" type="text/css" href="css/styles.css">
</head>
<body>
<h1 align="center" id="banner">Espress</h1>
<?php

  /* Connect to MySQL and select the database. */
  $connection = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD);

  if (mysqli_connect_errno()) echo "Failed to connect to MySQL: " . mysqli_connect_error();

  $database = mysqli_select_db($connection, DB_DATABASE);

  /* Ensure that tables exists. */
  VerifyTables($connection, DB_DATABASE);

  /* If input fields are populated, add a row to the Employees table. */
  $user_name = htmlentities($_POST['Name']);
  $user_email = htmlentities($_POST['Email']);

  if (strlen($user_name) && strlen($user_email)) {
    AddUser($connection, $user_name, $user_email);
    $user_name = '';
    $user_email = '';
  }
?>

<h2>Contact Information</h2>
<!-- Input form -->
<form action="<?PHP echo $_SERVER['SCRIPT_NAME'] ?>" method="POST">
  <table border="0">
    <tr>
      <td>Name</td>
      <td>Email</td>
    </tr>
    <tr>
      <td>
        <input type="text" name="Name" maxlength="45" size="30" />
      </td>
      <td>
        <input type="text" name="Email" maxlength="90" size="60" />
      </td>
      <td>
        <input type="submit" value="Signup" />
      </td>
    </tr>
  </table>
</form>

<h2>Interests</h2>
<form action="<?PHP echo $_SERVER['SCRIPT_NAME'] ?>" method="POST">
  <table border="0">
    <tr>
      <td>Interest</td>
    </tr>
    <tr>
      <td>
        <input type="text" name="Interest" maxlength="45" size="30" />
      </td>
      <td>
        <input type="submit" value="Add" id="add_interest_button"/>
      </td>
    </tr>
  </table>
</form>

<br /><br /><br />
<h4>DB:</h4>

<!-- Display table data. -->
<table border="1" cellpadding="2" cellspacing="2">
  <tr>
    <td>ID</td>
    <td>Name</td>
    <td>Email</td>
  </tr>

<?php

$result = mysqli_query($connection, "SELECT * FROM USERS");

while($query_data = mysqli_fetch_row($result)) {
  echo "<tr>";
  echo "<td>",$query_data[0], "</td>",
       "<td>",$query_data[1], "</td>",
       "<td>",$query_data[2], "</td>";
  echo "</tr>";
}
?>

</table>

<!-- Clean up. -->
<?php
  mysqli_free_result($result);
  mysqli_close($connection);
?>

<script>
  function test(){
    console.log("I AM HERE");
  }
</script>

</body>
</html>

<?php

/* Add an employee to the table. */
function AddUser($connection, $name, $email) {
   $n = mysqli_real_escape_string($connection, $name);
   $e = mysqli_real_escape_string($connection, $email);
  //  $check_query = sprintf("SELECT * FROM `USERS` (`Name`,`Email`) WHERE `Email` = '%s';",
  //  mysqli_real_escape_string($e));
   $check_query = "SELECT * FROM USERS WHERE Email = '$e'";
   $present = mysqli_query($connection, $check_query);
   $num_rows = mysqli_num_rows($present);
   if($num_rows<1){
     $query = "INSERT INTO `USERS` (`Name`,`Email`) VALUES ('$n', '$e');";
     if(!mysqli_query($connection, $query)) echo("<p>Error adding employee data.</p>");
   }else{
     echo "<script> test(); </script>";
   }
}

/* Check whether the table exists and, if not, create it. */
/*
	USERS

		ID	EMAIL	NAME

	INTERESTS
		ID 	NAME	USER

*/
function VerifyTables($connection, $dbName) {
  if(!TableExists("USERS", $connection, $dbName))
  {
     $query = "CREATE TABLE `USERS` (
         `ID` int(11) NOT NULL AUTO_INCREMENT,
         `Name` varchar(45) DEFAULT NULL,
         `Email` varchar(90) DEFAULT NULL,
         PRIMARY KEY (`ID`),
         UNIQUE KEY `ID_UNIQUE` (`ID`)
       ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1";

     if(!mysqli_query($connection, $query)) echo("<p>Error creating users table.</p>");
  }

//     if(!TableExists("INTERESTS", $connection, $dbName))
//   {
//      $query = "CREATE TABLE `INTERESTS` (
//          `ID` int(11) NOT NULL AUTO_INCREMENT,
//          `Interest` varchar(45) DEFAULT NULL,
//          `User_Id` int(11) NOT NULL,
//          PRIMARY KEY (`ID`),
//          FOREIGN KEY (`User_Id`),
//          UNIQUE KEY `ID_UNIQUE` (`ID`)
//        ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1";
//
//      if(!mysqli_query($connection, $query)) echo("<p>Error creating interests table.</p>");
//   }
}


/* Check for the existence of a table. */
function TableExists($tableName, $connection, $dbName) {
  $t = mysqli_real_escape_string($connection, $tableName);
  $d = mysqli_real_escape_string($connection, $dbName);

  $checktable = mysqli_query($connection,
      "SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_NAME = '$t' AND TABLE_SCHEMA = '$d'");

  if(mysqli_num_rows($checktable) > 0) return true;

  return false;
}
?>
