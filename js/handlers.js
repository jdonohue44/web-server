document.getElementById('add_interest_button').disabled = true;
document.getElementById('interest_text').disabled = true;
document.getElementById("signup_button").addEventListener("click", enableInput);
function enableInput(){
  document.getElementById('add_interest_button').disabled = false;
  document.getElementById('interest_text').disabled = false;
}
