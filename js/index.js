// document.getElementById('signup').addEventListener('click',(e)=>{
//     //e.preventDefault();
//     let form = '<form action="./accounts/signup.php" method="post" class="signup-form">'
//     +'<h4>Sign up</h4><br><br>'
//     +'<label for="accno">First name:</label>'
//     +'<input type="text" id="fname" name="fname" placeholder="Enter your first name" required><br><br>'
//
//     +'<label for="accno">Last name:</label>'
//     +'<input type="text" id="lname" name="lname" placeholder="Enter your last name" required><br><br>'
//     +'<small id="errr"></small>'
//
//     +'<label for="accno">Account number:</label>'
//     +'<input type="text" id="accno" name="accno" placeholder="Enter account number" required><br><br>'
//     +'<small id="errr"></small>'
//
//     +'<label for="lname">PIN:</label>'
//     +'<input type="password" id="pin" name="pin" placeholder="Your pin" required><br><br>'
//
//     +'<input type="submit" value="Sign up">'
//     +'</form>';
//     document.querySelector('.onright').innerHTML= form;
//     //alert(document.querySelector('.onleft').innerHTML= form);
//     console.log("btn clicked");
// });

document.querySelector('#reset').addEventListener('click',(e)=>{
  e.preventDefault();
  let form = '<form action="./accounts/change_pin.php" method="post" class="signup-form">'
  +'<br><br>'
  +'<h4>Reset password</h4><br><br>'
  +'<label for="accno">Account number:</label>'
  +'<input type="number" id="accno" name="accno" placeholder="Enter account number" required><br><br>'
  +'<input type="submit" value="send password">'
  +'</form>';

  console.log("clicked");
  document.querySelector('.onright').innerHTML= form;
});
