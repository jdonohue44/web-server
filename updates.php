<?php
function updateInterestsTable(){
  $query = "INSERT INTO `USERS` (`Name`,`Email`) VALUES ('$n', '$e');";
  if(!mysqli_query($connection, $query)) echo("<p>Error adding employee data.</p>");
}
?>
