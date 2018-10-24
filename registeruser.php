<?php require_once '../banking/accounts/accounts.php';
      require_once '../banking/database/db.php';
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title></title>
    <style>
    /* form {
        background: white } */
    form {
        border-radius: 1em;
        padding: 1em;
        position: absolute;
        top: 50%;
        left: 50%;
        margin-right: -50%;
        transform: translate(-50%, -50%) }
  </style>
  </head>
  <body>
    <?php
     if(isset($_POST['submit'])){
      $fname = "ururu";//$_POST['fname'];
      $lname = "dggdgd";//$_POST['lname'];
      $accno = "232323243456564";//$_POST['accno'];
      $pin = "2344";//md5($_POST['pin']);

      $reg = new RegisterUser($fname,$lname,$accno,$pin);
      if($reg->register($conn)){
        echo "user registered";
      }else{
        echo "registration failed";
      }

      echo $accno;
    }
     ?>
    <form class=".signup" action=<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?> method="post">
      <h4>Sign up</h4><br><br>
      <label for="accno">First name:</label>
      <input type="text" id="fname" name="fname" placeholder="Enter your first name" required><br><br>

      <label for="accno">Last name:</label>
      <input type="text" id="lname" name="lname" placeholder="Enter your last name" required><br><br>

      <label for="accno">Account number:</label>
      <input type="text" id="accno" name="accno" placeholder="Enter account number" required><br><br>

      <label for="lname">PIN:</label>
      <input type="password" id="pin" name="pin" placeholder="Your pin" required><br><br>

      <input type="submit" name="submit" value="Sign up">
    </form>
  </body>
</html>
