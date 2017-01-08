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

    VerifyInterestTable($connection, DB_DATABASE);

    $user_interests = $_POST['interests'];
    if(sizeof($user_interests)>0){
      for($i = 0; $i < sizeof($user_interests); $i++){
          AddInterest($connection, $user_interests[$i]);
          AddUserInterest($connection, $user_name, $user_interests[$i]);
      }
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

<?php
/* Add an employee to the table. */
function AddInterest($connection, $interest) {
   $i = mysqli_real_escape_string($connection, $interest);
  //  $check_query = sprintf("SELECT * FROM `USERS` (`Name`,`Email`) WHERE `Email` = '%s';",
  //  mysqli_real_escape_string($e));
   $check_query = "SELECT * FROM INTERESTS WHERE Interest = '$i'";
   $present = mysqli_query($connection, $check_query);
   $num_rows = mysqli_num_rows($present);
   if($num_rows<1){
     $query = "INSERT INTO `INTERESTS` (`Interest`) VALUES ('$i');";
     if(!mysqli_query($connection, $query)) echo("<p>Error adding interest data.</p>");
    }
}

function AddUserInterest($connection, $name, $interest) {
   $n = mysqli_real_escape_string($connection, $name);
   $i = mysqli_real_escape_string($connection, $interest);
  //  $check_query = sprintf("SELECT * FROM `USERS` (`Name`,`Email`) WHERE `Email` = '%s';",
  //  mysqli_real_escape_string($e));
  //  $check_query = "SELECT * FROM USER_INTERESTS WHERE Interest_ID = '$i'";
  //  $present = mysqli_query($connection, $check_query);
  //  $num_rows = mysqli_num_rows($present);
  //  if($num_rows<1){
     $query = "INSERT INTO `USER_INTERESTS` (`User_ID`, `Interest_ID`) VALUES (1,2);";
     if(!mysqli_query($connection, $query)) echo("<p>Error adding interest data.</p>");
    // }
}

function VerifyInterestTable($connection, $dbName){
  if(!TableExists("INTERESTS", $connection, $dbName))
  {
     $query = "CREATE TABLE `INTERESTS` (
         `ID` int(11) NOT NULL AUTO_INCREMENT,
         `Interest` varchar(45) DEFAULT NULL,
         PRIMARY KEY (`ID`),
         UNIQUE KEY `ID_UNIQUE` (`ID`)
       ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1";

     if(!mysqli_query($connection, $query)) echo("<p>Error creating interests table.</p>");
  }
  if(!TableExists("USER_INTERESTS", $connection, $dbName))
  {
     $query = "CREATE TABLE `USER_INTERESTS` (
         `User_ID` int(11) NOT NULL,
         `Interest_ID` int(11) NOT NULL
       )ENGINE=InnoDB DEFAULT CHARSET=latin1";

     if(!mysqli_query($connection, $query)) echo("<p>Error creating user interests table.</p>");
  }
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
