<?php
session_start();
 ?>
<html>
<head>
  <link rel="stylesheet" type="text/css" href="./css/styles.css">
</head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<body>
  <div class="centered-div">
    <div class="thanks_logo_img">
      <a href="http://54.86.139.119/">
        <img src="http://mason.gmu.edu/~jdonohu2/logo1.png" id="thanks_mobile_img_src" alt="espress news homepage"></img>
      </a>
    </div>
    <?php
    if($_SESSION["unsubscribe"]){
        echo "<h1 id='thanks-page-title'>Successfully unsubscribed " . $_SESSION["email"] . ".</h1>";
    }else{
      echo "<h1 id='thanks-page-title'> All set! Your newsletter will arrive soon.</h1>";
    }
    ?>
  </div>
</body>
</html>
