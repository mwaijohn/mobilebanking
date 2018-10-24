<?php session_start(); ?>
<?php if(!(isset($_SESSION['accno']))){ header("location:../index.php");} ?>
<?php
require_once '../database/db.php';
require_once 'accounts.php';
require_once 'create_account.php';

if($_SERVER['REQUEST_METHOD']=='POST'){
  $fname = $_POST['fname'];
  $lname = $_POST['lname'];
  $accno = $_POST['accno'];
  $email = $_POST['email'];
  $pin = $_POST['pin'];
  $registeredby = $_SESSION['accno'];

  $regInstance = new RegisterUser($fname,$lname,$accno,$pin,$email);
  if($regInstance->register($conn,$registeredby)==true){
    $ggggggg = $accno;
    $cr_account = new CreateAccount($fname,$lname,$ggggggg,0);
    //create account and redirect to login page
    if($cr_account->saveAccount($conn)==true){
        $_SESSION['signupsuccess'] = '<div class="success" ><small class="error" style="color:green;">'.'User registered successfully. </small><strong> User pin is ' .$pin . '</strong></div>';
        header("location:./signup.php");
    }else{
      $_SESSION['signuperror'] = "Sign up failed";
      header("location:./signup.php");
    }
    //$conn = null;
  }else{
    // echo "<script>alert('seems you have already registered')</script>";
    // header("location:./signup.php");
    $_SESSION['signuperror'] = "<strong>Sign up failed you are already registered</strong>";
    header("location:./signup.php");
  }
}
 ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <meta charset="utf-8">
  <title>Create new account</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
  integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO"
    crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.1/css/all.css"
     integrity="sha384-5sAR7xN1Nv6T6+dT2mhtzEpVJvfS3NScPQTrOxhwjIuvcA67KV2R5Jz6kr4abQsz"
      crossorigin="anonymous">
    <link rel="stylesheet" href="../css/signup.css">
</head>

<body>
  <div class="header">
    <img class="logo" src="../logo.png" alt="Mobile bank" height="80px" />
    <p class="title">Self <span>Service Portal</span></p>
  </div>
  <div class="sform">
    <h4>Register User</h4>
    <?php if(isset($_SESSION['signuperror'])) {echo $_SESSION['signuperror'];}?>
    <?php if(isset($_SESSION['signupsuccess'] )) {echo$_SESSION['signupsuccess'] ;}?>
    <form action=<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?> method="post" class="signup-form">
      <label for="accno">First name:</label>
      <input type="text" id="fname" name="fname" placeholder="Enter your first name" required><br>

      <label for="accno">Last name:</label>
      <input type="text" id="lname" name="lname" placeholder="Enter your last name" required><br>
      <small id="errr"></small>

      <label for="accno">Account number:</label>
      <input type="text" id="accno" name="accno" placeholder="Enter account number" required><br>
      <small id="errr"></small>

      <label for="email">Email:</label>
      <input type="email" id="email" name="email" placeholder="Enter email address" required><br>
      <small id="errr"></small>

      <label for="lname">PIN:</label>
      <input type="password" id="pin" name="pin" placeholder="Your pin" required><br><br>

      <input type="submit" value="Sign up">
    </form>
    <a href="../index.php"><i class="fa fa-chevron-circle-left"></i>Home</a>
  </div>
</body>

</html>
