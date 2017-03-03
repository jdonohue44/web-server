<?php
include "../inc/dbinfo.inc";
session_start();
?>
<html>
<head>
  <link rel="stylesheet" type="text/css" href="./css/styles.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
</head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<body>
  <?php
    print_r($_SESSION);
    $name  = $_SESSION["name"];
    $email = $_SESSION["email"];

    echo "<p>" + $email + "</p>";

    if(!strlen($email)){
      header("Location: http://54.86.139.119/");
    }

    if(strlen($name)){
      $username = $name;
    }else{
      $username = $email;
    }

    /* Connect to MySQL and select the database. */
    $connection = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD);
    if (mysqli_connect_errno()) echo "Failed to connect to database: " . mysqli_connect_error();

    VerifyInterestTable($connection, DB_DATABASE);
    $array = GetInterests($connection, $email);


    // if(sizeof($array)>0){
    //   for($i = 0; $i < sizeof($array); $i++){
    //       DeleteUserInterest($connection, $email, $array[$i]);
    //   }
    // }

    $user_interests = $_POST['interests'];
    if(sizeof($user_interests)>0){
      for($i = 0; $i < sizeof($user_interests); $i++){
          AddInterest($connection, $user_interests[$i]);
          AddUserInterest($connection, $email, $user_interests[$i]);
      }
      header("Location: http://54.86.139.119/Thanks.html");
    }

    ?>

    <div class="nav-bar-flex-row">

      <div class="logo_img">
        <a href="http://54.86.139.119/">
          <img src="http://mason.gmu.edu/~jdonohu2/logo1.png" alt="espress news home"></img>
        </a>
      </div>

      <div class="login-container">
        <h4 class="banner-sm">
           <span id="username"><?php echo $username ?></span>
         </h1>
      </div>

    </div>

    <h1 id="interest-page-title">Interests</h1>
    <h2 id="interest-page-subtitle">More interests means more articles in your newsletter!</h2>

    <div class = "add_interest_container">
      <input id="interest_text" type="text" name="Interest" placeholder="Next Interest" maxlength="30" size="30" />
      <button type="button" id="add_interest_button">Add</button>
    </div>

    <div class="interest-form-container">
      <div class="interest-form">
        <form action="<?PHP echo $_SERVER['SCRIPT_NAME'] ?>" method="POST">
            <ul id="interests_ul">
              <?php
              if(sizeof($array) > 0){
                foreach ($array as $v) {
                  echo "<li>" . $v . "<input type='hidden' name='interests[]' value='" . $v . "'/></li>";
                }
              }
            ?>
            </ul>
      </div>
    </div>

    <div class="submit_button_container">
      <input type="submit" name="submit_button" value="Submit" />
      </form>
    </div>


  <script>
    document.getElementsByName('submit_button')[0].disabled = true;
    checkListLength();

    $('#add_interest_button').click(function(){
      var interest = $.trim($('#interest_text').val());
      if(interest.length > 0){
        $('#interests_ul').append("<li>"+interest+"<input type='hidden' name='interests[]' value='"
        +interest+"'/></li>");
        $('#interest_text').val('');
        checkListLength();
        initializeListItems()
      }
    });

    function initializeListItems(){
      $('ul#interests_ul li').click(function(){
          $(this).remove();
          checkListLength();
      });

      $('ul#interests_ul li').hover(function(){
        $(this).css("background-color", "#ff4444");
        },function(){
        $(this).css("background-color", "#999");
      });
    }

    function checkListLength(){
      var interest_lis = $("#interests_ul li");
      if(interest_lis.length > 0) {
        document.getElementsByName('submit_button')[0].style.backgroundColor = "#3897F0";
        document.getElementsByName('submit_button')[0].disabled = false;
        $(".interest-form").css("background-color","#fafafa");
        $("#helper-text").remove();
      }else{
        $(".interest-form").css("background-color","#e6e6e6");
        $(".interest-form").append("<h3 id='helper-text'>added interests go here.</h3>");
      }
    }

    initializeListItems();
  </script>

</body>
</html>

<?php
/* Get user interests */
function GetInterests($connection, $email) {
   $e = mysqli_real_escape_string($connection, $email);
   $array = array();
   echo "<p>" + $e + "</p>";
   echo "<p>" + $email + "</p>";
   $query = "SELECT INTERESTS.Interest FROM USER_INTERESTS
	                 INNER JOIN USERS ON USERS.ID = USER_INTERESTS.User_ID
	                 INNER JOIN INTERESTS ON INTERESTS.ID = USER_INTERESTS.Interest_ID
	                 where USERS.Email = '$e'";

   $result = mysqli_query($connection, $query);
   $num_rows = mysqli_num_rows($result);
   if ($num_rows > 0) {
     while($row = mysqli_fetch_assoc($result)) {
        array_push($array, $row["Interest"]);
      }
   }
   return $array;
}

/* Add interests to INTEREST table. */
function AddInterest($connection, $interest) {
   $i = mysqli_real_escape_string($connection, $interest);
  //  $check_query = sprintf("SELECT * FROM `USERS` (`Name`,`Email`) WHERE `Email` = '%s';",
  //  mysqli_real_escape_string($e));
   $check_query = "SELECT * FROM INTERESTS WHERE Interest = '$i'";
   $present = mysqli_query($connection, $check_query);
   $num_rows = mysqli_num_rows($present);
   if($num_rows<1){
     $query = "INSERT INTO INTERESTS (Interest) VALUES ('$i');";
     if(!mysqli_query($connection, $query)) echo("<p>Error adding interest data.</p>");
    }
}

/*Link User with INTEREST in the join table USER_INTERESTS*/
function AddUserInterest($connection, $email, $interest) {
   $e = mysqli_real_escape_string($connection, $email);
   $i = mysqli_real_escape_string($connection, $interest);

   $user_result = mysqli_query($connection, "SELECT USERS.ID FROM USERS WHERE USERS.Email = '$e'");
   $user_id = mysqli_fetch_object($user_result)->ID;
   $interest_result = mysqli_query($connection, "SELECT INTERESTS.ID FROM INTERESTS WHERE INTERESTS.Interest = '$i'");
   $interest_id = mysqli_fetch_object($interest_result)->ID;

   $query = "INSERT INTO USER_INTERESTS (User_ID, Interest_ID) VALUES ($user_id, $interest_id);";
   if(!mysqli_query($connection, $query)) echo("<p>Error adding user interest data.</p>");
}

function DeleteUserInterest($connection, $email, $interest) {
  $e = mysqli_real_escape_string($connection, $email);
  $i = mysqli_real_escape_string($connection, $interest);

  $user_result = mysqli_query($connection, "SELECT USERS.ID FROM USERS WHERE USERS.Email = '$e'");
  $user_id = mysqli_fetch_object($user_result)->ID;

  $query = "DELETE FROM USER_INTERESTS WHERE User_ID = $user_id;";
  if(!mysqli_query($connection, $query)) echo("<p>Error deleting user interest data.</p>");
}

function VerifyInterestTable($connection, $dbName){
  if(!TableExists("INTERESTS", $connection, $dbName))
  {
     $query = "CREATE TABLE `INTERESTS` (
         ID int(11) NOT NULL AUTO_INCREMENT,
         Interest varchar(45) DEFAULT NULL,
         PRIMARY KEY (ID),
         UNIQUE KEY ID_UNIQUE (ID)
       ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1";

     if(!mysqli_query($connection, $query)) echo("<p>Error creating interests table.</p>");
  }
  if(!TableExists("USER_INTERESTS", $connection, $dbName))
  {
     $query = "CREATE TABLE USER_INTERESTS (
         User_ID int(11) NOT NULL,
         Interest_ID int(11) NOT NULL,
         PRIMARY KEY (USER_ID,Interest_ID)
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
