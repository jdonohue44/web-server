<?php
include "../inc/dbinfo.inc";
session_start();
?>
<html>
<head>
  <meta charset=UTF-8>
  <meta name="description" content="Espress News">
  <meta name="keywords" content="Espress, espress, express news, Espress News,">
  <meta name="author" content="Espress">
  <link rel="stylesheet" type="text/css" href="./css/styles.css">
  <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
</head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<body class="interests-page-body">
  <?php
    $name  = $_SESSION["name"];
    $email = $_SESSION["email"];


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
    $database   = mysqli_select_db($connection, DB_DATABASE);

    VerifyInterestTable($connection, DB_DATABASE);

    $array = GetInterests($connection, $email);

    $user_interests = $_POST['interests'];
    if(sizeof($user_interests)>0){
      if(sizeof($array)>0){
        for($i = 0; $i < sizeof($array); $i++){
            DeleteUserInterest($connection, $email, $array[$i]);
        }
      }
      for($i = 0; $i < sizeof($user_interests); $i++){
          AddInterest($connection, $user_interests[$i]);
          AddUserInterest($connection, $email, $user_interests[$i]);
      }
      header("Location: http://54.86.139.119/Thanks.html");
    }

    ?>

    <div class="nav-bar-flex-row">
      <div class="logo_img_interests">
        <a href="http://54.86.139.119/">
          <img class="espress_news_img" src="http://mason.gmu.edu/~jdonohu2/logo1.png" alt="espress news home"></img>
        </a>
      </div>

      <div class="icon-group">
        <div style="padding: 0 12px 0 12px;">
          <a href="http://54.86.139.119/" style="color:black;">
            <i class="fa fa-home fa-2x" aria-hidden="true"></i>
          </a>
        </div>
        <div id="user-icon" style="padding: 0 12px 0 12px;">
          <i class="fa fa-user fa-2x" aria-hidden="true"></i>
        </div>
        <div id="info-icon" style="padding: 0 12px 0 12px;">
          <i class="fa fa-info-circle fa-2x" aria-hidden="true"></i>
        </div>
      </div>
    </div>
      <div class="modal">
      </div>

    <div class="hidden-nav-bar-flex-row">
      <div class="hidden-icon-group">
        <div>
          <a href="http://54.86.139.119/" style="color:black;">
            <i class="fa fa-home fa-2x" aria-hidden="true"></i>
          </a>
        </div>
        <div>
          <a href="http://54.86.139.119/" style="color:black;">
            <i class="fa fa-user fa-2x" aria-hidden="true"></i>
          </a>
        </div>
        <div>
          <a href="http://54.86.139.119/" style="color:black;">
            <i class="fa fa-info-circle fa-2x" aria-hidden="true"></i>
          </a>
        </div>
      </div>
    </div>

    <h1 id="interest-page-title">Interests</h1>
    <h2 id="interest-page-subtitle">More interests means more articles in your newsletter!</h2>

    <div class = "add_interest_container">
      <input id="interest_text" type="text" name="Interest" placeholder="Next Interest" spellcheck="true" maxlength="30" size="30" />
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
    $('.icon-group #user-icon').hover(function(){
      $('.modal').css("display","flex");
      $('.modal').text("You are signed in as <?php echo $name?>.");
    },function(){
      $('.modal').css("display","none");
      $('.modal').text("");
    });

    $('.icon-group #info-icon').hover(function(){
      $('.modal').css("display","flex");
      $('.modal').text("Espress news will send you an email with articles relating to your interests.");
    },function(){
      $('.modal').css("display","none");
      $('.modal').text("");
    });

    document.getElementsByName('submit_button')[0].disabled = true;
    checkListLength();
    if($(window).width() < 650){
      $( "#interest_text" ).focus(function() {
        $(".submit_button_container").hide();
      });
      $("#interest_text").focusout(function(){
        $(".submit_button_container").show();
      });
    }

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
        $(this).css("background-color", "#fff");
      });
    }

    function checkListLength(){
      var interest_lis = $("#interests_ul li");
      if(interest_lis.length > 0) {
        document.getElementsByName('submit_button')[0].style.backgroundColor = "#3897F0";
        document.getElementsByName('submit_button')[0].disabled = false;
        $('#helper-text').remove();
        if($(window).width() > 650){
          $(".interest-form").css("background-color","#f7f7f7");
        }
      }else{
        if($(window).width() > 650){
          $(".interest-form").css("background-color","#e6e6e6");
          $(".interest-form").append("<h3 id='helper-text'>added interests go here.</h3>");
        }
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
   $query = "SELECT INTERESTS.Interest FROM USER_INTERESTS
             INNER JOIN USERS ON USERS.ID = USER_INTERESTS.User_ID
             INNER JOIN INTERESTS ON INTERESTS.ID = USER_INTERESTS.Interest_ID
             where USERS.Email = '$e'";

   $result = mysqli_query($connection, $query);
   if(!$result) echo("<p>" . mysqli_error($connection) . "</p>");
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
