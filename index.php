<?php
function connect_to_mysql() {
    $conn = mysqli_connect('dubai.csuhsua8cx8a.us-east-1.rds.amazonaws.com','jdonohue44','dubaiguy$$','Espresso')
    or die('Error connecting to MySQL server.');
    return $conn;
  }

function insert_customer_if_not_exists($conn, $email) {
  $query = "INSERT IGNORE INTO Customer (email) VALUES ('".$email."')";
  $conn->query($query);
}

function retrieve_customer($conn, $email) {
  $query = "SELECT * FROM Customer WHERE email = ?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("i", $email);
  $stmt->execute();
  $result = $stmt->get_result(); // get the mysqli result
  $customer = $result->fetch_assoc(); // fetch the data
  return $customer;
}

function disconnect_from_mysql($conn) {
  mysqli_close($conn);
}

$conn = connect_to_mysql();
if(isset($_POST['validate_button']))
{
   session_start();
   $email = $_POST['Email'];
   $_SESSION['customer_email'] = $email;
   insert_customer_if_not_exists($conn, $email);
   disconnect_from_mysql($conn);
   header('Location: Interests.php');
}

?>

<html>
<head>
  <meta charset=UTF-8>
  <meta name="description" content="Espresso News">
  <meta name="keywords" content="personalized news, Espresso, espresso, express news, Espresso News, newsletter">
  <meta name="author" content="Espresso">
  <link rel="stylesheet" type="text/css" href="./css/styles.css">
  <link rel="shortcut icon" type="image/png" href="https://espress.s3.amazonaws.com/img/coffee_cup_sm.png"/>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
</head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<body>
  <noscript>
    <h3 align="center" style="color:#424242;">JavaScript Disabled. Check your browser settings.</h3>
  </noscript>

<div class="flex_box_holder">

    <div class="testimonials-container">
      <div class="sample_img">
      </div>
    </div>

    <div class="sign_in_container">

        <div class="sign_in_form">
          <div class="logo_img_home">
            <img src="https://espress.s3.amazonaws.com/img/espress_logo_banner_login.png"></img>
          </div>

          <h2 class="espress-title-message">We find articles on the things you care about and deliver them neatly to your inbox. Just tell us where to send your newsletter.</h2>
          <!-- <h4 class="espress-title-sub-message"></h4> -->
          <!-- Input form -->
          <form method="POST" action="index.php" >
            <div id="email_box">
              <input type="text" name="Email" id="email_text" placeholder="atticuscobain@gmail.com" autocorrect="off" autocapitalize="off" tabindex=2 maxlength="35" size="40" />
            </div>

            <div class="signup-button-container">
              <input type="submit" name="validate_button" value="Lets go!" />
            </div>
          </form>
        </div>

        <div class="info">
          <p id="info_paragraph">
            Expect an email from espressmorningnews@gmail.com
          </p>
        </div>

        <div class="footer">
          <p style="color: #999"> &copy; Espresso News, 2020</p>
        </div>

    </div>
</div>

<script>
  document.getElementsByName('validate_button')[0].disabled = true;

  $(document).ready(function() {
    $('#email_text').val('');
    $('#name_text').val('');
  });

  // enable Validate button when Email is Valid
  $('#email_text').bind('input propertychange', function() {
    var email = $('#email_text').val();
    if (validateEmail(email)) {
      $("#email_text").css("border-color", "green");
      document.getElementsByName('validate_button')[0].disabled = false;
      document.getElementsByName('validate_button')[0].style.backgroundColor = "#3897F0";
    } else {
      if(!email.length){
        $("#email_text").css("border-color", "#ccc");
        document.getElementsByName('validate_button')[0].disabled = true;
      }else{
        $("#email_text").css("border-color", "red");
        document.getElementsByName('validate_button')[0].disabled = true;
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
