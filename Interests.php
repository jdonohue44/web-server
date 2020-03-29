<?php
function connect_to_mysql() {
    $conn = mysqli_connect('dubai.csuhsua8cx8a.us-east-1.rds.amazonaws.com','jdonohue44','dubaiguy$$','Espresso')
    or die('Error connecting to MySQL server.');
    return $conn;
  }

function retrieve_customer($conn, $email) {
  $query = "SELECT * FROM Customer WHERE email = '" . mysqli_escape_string($conn,$email) . "';";
  $result = mysqli_query($conn, $query);
  $customer = mysqli_fetch_assoc($result);
  return $customer;
}

function update_news_interests_for_customer($conn, $email, $news_interests) {
  $query = "UPDATE Customer SET news_interests =  '" . mysqli_escape_string($conn, $news_interests) . "' WHERE  email = '" . mysqli_escape_string($conn,$email) . "';";
  $conn->query($query);
}

function map_array_to_obj($arr) {
  $object = new stdClass();
  foreach ($arr as $key => $value)
  {
      $object->$value = 1;
  }
  return $object;
}

function disconnect_from_mysql($conn) {
  mysqli_close($conn);
}

session_start();
$customer_email =  $_SESSION['customer_email'];

$conn = connect_to_mysql();
$customer = retrieve_customer($conn, $customer_email);
$customer_news_interests = $customer["news_interests"];

if(isset($_POST['submit_button']))
{
   $interests = $_POST['interests'];
   $interests_json = json_encode(map_array_to_obj($interests), JSON_UNESCAPED_SLASHES);
   update_news_interests_for_customer($conn, $customer_email, $interests_json);
   disconnect_from_mysql($conn);
   header('Location: Thanks.html');
}

?>

<html>
<head>
  <meta charset=UTF-8>
  <meta name="description" content="Espresso News">
  <meta name="keywords" content="personalized news, Espresso, espresso, express news, Espresso News, newsletter">
  <meta name="author" content="Espresso">
  <link rel="stylesheet" type="text/css" href="./css/styles.css">
  <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
</head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<body class="interests-page-body">

    <div class="nav-bar-flex-row">
      <div class="logo_img_interests">
        <a href="http://www.myespressonews.com">
          <img class="espress_news_img" src="https://espress.s3.amazonaws.com/img/espress_logo_banner_login.png" alt="espress news home"></img>
        </a>
      </div>

      <div class="icon-group">
        <div id="home-icon" style="padding: 0 12px 0 12px;">
          <a href="http://www.myespressonews.com" style="color:black;">
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
          <a href="http://www.myespressonews.com" style="color:black;">
            <i class="fa fa-home fa-2x" aria-hidden="true"></i>
          </a>
        </div>
        <div>
          <a href="http://www.myespressonews.com" style="color:black;">
            <i class="fa fa-user fa-2x" aria-hidden="true"></i>
          </a>
        </div>
        <div>
          <a href="http://www.myespressonews.com" style="color:black;">
            <i class="fa fa-info-circle fa-2x" aria-hidden="true"></i>
          </a>
        </div>
      </div>
    </div>

    <h1 id="interest-page-title">What do you want news on?</h1>
    <h2 id="interest-page-subtitle">It can be anything! Wombats, surfing, tulip season, George Clooney ...</h2>

    <div class = "add_interest_container">
      <input id="interest_text" type="text" name="Interest" placeholder="Next Interest" spellcheck="true" maxlength="30" size="30" />
      <button type="button" id="add_interest_button">Add</button>
    </div>

    <div class="interest-form-container">
      <div class="interest-form">
        <form method="POST">
            <ul id="interests_ul">
            </ul>
      </div>
    </div>

    <div class="submit_button_container">
      <input type="submit" name="submit_button" value="Submit" />
      </form>
    </div>


  <script>
    var customerEmail = '<?php echo $customer_email; ?>';
    $('.icon-group #user-icon').hover(function(){
      $('.modal').css("display","flex");
      $('.modal').text("Your newsletter will be sent to " + customerEmail);
    },function(){
      $('.modal').css("display","none");
      $('.modal').text("");
    });

    $('.icon-group #info-icon').hover(function(){
      $('.modal').css("display","flex");
      $('.modal').text("Espresso News will send you an email with articles relating to your interests");
    },function(){
      $('.modal').css("display","none");
      $('.modal').text("");
    });

    $('.icon-group #home-icon').hover(function(){
      $('.modal').css("display","flex");
      $('.modal').text("Return to the home page");
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
        $(this).css("border-color", "#ff4444");
        },function(){
          $(this).css("border-color", "#6d6d6d");
      });
    }

    function checkListLength(){
      var interest_lis = $("#interests_ul li");
      if (interest_lis.length > 0) {
        document.getElementsByName('submit_button')[0].style.backgroundColor = "#3897F0";
        document.getElementsByName('submit_button')[0].disabled = false;
        $('#helper-text').remove();
        if ($(window).width() > 650) {
          $(".interest-form").css("background-color","#f7f7f7");
        }
      } else{
        if($(window).width() > 650) {
          $(".interest-form").css("background-color","#e6e6e6");
          $(".interest-form").append("<h3 id='helper-text'>added interests go here.</h3>");
        }
      }
    }

    initializeListItems();
  </script>

</body>
</html>
