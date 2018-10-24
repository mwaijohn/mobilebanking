<?php
require_once '../database/db.php';
require_once 'accounts.php';
//session_destroy();
session_start();

if($_SERVER['REQUEST_METHOD']=='POST'){
  $accno = $_POST['accno'];
  $pin = $_POST['pin'];

  // $regInstance = new RegisterUser("john","mwai","91072728182","1111");
  // $regInstance->register($conn);

  //create object to check login details and start session if details correct
  $user = new Login($pin,$accno);
  $isLogged = $user->logUser($conn);

  if($isLogged==true){
    $_SESSION['accno'] = $accno;

    //echo $_SESSION['fname'].$_SESSION['lname'].$_SESSION['accno'];
    header("location:../utility.php");
    //echo $_SESSION['accno'];
  }else{
      $_SESSION['err'] = "Wrong details";
      header("location:../index.php");

  }
}
?>
