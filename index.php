<?php
session_start();
if(isset($_SESSION['accno'])){
  header("location:utility.php");
}
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="utf-8">
  <title>Mobile banking</title>
  <link rel="stylesheet" href="./css/style.css">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
</head>

<body>
  <div id="header" class="header">
    <!-- <a id="logo" href="localhost/banking"><img src="logo.png" alt="Mobile bank" /></a> -->
    <!-- <span class="title">Self <span>Service Portal</span></span> -->
    <img class="logo" src="logo.png" alt="Mobile bank" height="80px"/>
        <p class="title">Self <span>Service Portal</span></p>
  </div>
  <div class="content">
    <div class="onleft">
      <div class="tips">
        <h3>Financial Tips</h3>
        <ul id="sec-tips">
          <li class="tips-items">Contribute to a Retirement Plan</li>
          <li class="tips-items">Have a Savings Plan.</li>
          <li class="tips-items">Get Paid What You're Worth and Spend Less Than You Earn</li>
          <li class="tips-items">Review Your Insurance Coverages</li>
          <li class="tips-items">Invest!</li>
          <li class="tips-items">Maximize Your Employment Benefits</li>
        </ul>
      </div>
    </div>
    <div class="onright">
      <form action="./accounts/logger.php" method="post" class="login-form">
        <h4 style="padding:0px;">Login here using account number and PIN</h4><br><br>
        <?php if(isset( $_SESSION['err'])){
          echo '<div class="error" >'.$_SESSION['err'].'</div>';}else{echo "";} unset($_SESSION['err']);
          ?>
        <?php
         if(isset( $_SESSION['signupsuccess'])){echo '<div class="success" ><small class="error" style="color:green;">'.$_SESSION['signupsuccess'].'</small></div><br>';}else{echo "";} unset($_SESSION['signupsuccess']);
         if(isset( $_SESSION['pinchanged'])){echo '<div class="success" ><small class="error" style="color:green;">'.$_SESSION['pinchanged'].'</small></div><br>';}else{echo "";} unset($_SESSION['pinchanged']);
         if(isset( $_SESSION['pinchangedf'])){echo '<div class="success" ><small class="error" style="color:red;">'.$_SESSION['pinchangedf'].'</small></div><br>';}else{echo "";} unset($_SESSION['pinchangedf']);

         ?>
        <label for="accno">Account number:</label>
        <input type="text" id="accno" name="accno" placeholder="Enter account number" autocomplete="off" required><br><br><br>
        <small id="errr"></small>

        <label for="lname">PIN:</label>
        <input type="password" id="pin" name="pin" placeholder="Your pin" autocomplete="off" required><br><br>

        <input type="submit" value="Sign in">
        <br><br>

        <!-- <input type="button" value="Dont have an Account? Register" id="signup"> -->
      </form>
      <small style="margin-left: 50px;">change pin number?<a id="reset" href="#"style="color:blue">reset here</a></small>
      <!-- <script> window.location= 'google.com'</script> -->

    </div>
  </div>
  <script src="./js/index.js"></script>
</body>

</html>
