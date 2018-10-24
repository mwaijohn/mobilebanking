<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// require_once '../PHPMailer/src/PHPMailer.php';
// require_once '../PHPMailer/src/SMTP.php';
// require_once '../PHPMailer/src/Exception.php';

require '../vendor/autoload.php';
require_once '../database/db.php';

class ChangePass{
  function __construct(){}
  static function changePass($connection,$accno){
    $sql = "SELECT email,accno from _userdetails WHERE accno=:accno";
    $stmt = $connection->prepare($sql);
    $stmt->execute(array(':accno'=>$accno));

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    //echo $result['email'];

    //generate pin
    $pin = rand(0,9).rand(0,9).rand(0,9).rand(0,9);
    //echo $pin;
    //update pin
    $update_sql = "UPDATE _userdetails SET pin=:pin WHERE accno=:accno";
    $u_stmt = $connection->prepare($update_sql);
    $u_stmt->execute(array(':pin'=>md5($pin),':accno'=>$accno));

    //mail password
    if($result['email']){
      ChangePass::mailer($pin,$result['email']);
      return true;
    }

    return false;
  }

  static function mailer($password,$to){
    $mail = new PHPMailer();
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = '****************';
    $mail->Password = '*************';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;
    $mail->isHTML(true);
    $mail->SMTPOptions = array(
       'ssl' => array(
         'verify_peer' => false,
         'verify_peer_name' => false,
         'allow_self_signed' => true
        )
      );
    $mail->setFrom('******************', 'admin');
    $mail->addAddress($to, 'password reset');
    $mail->Subject  = 'Password reset';
    $mail->Body = '<p>You have requested to change password</p>
    <strong>Your new password is: '.$password.'</strong><br>
    <small>Login to your acccount to change password</small>';
    if(!$mail->send()) {
      //echo 'Mailer error: ' . $mail->ErrorInfo;
      return false;
    } else {
      return true;
    }
  }
}

if(isset($_POST['accno'])){
  $accno = $_POST['accno'];
  if(ChangePass::changePass($conn,$accno)== true){
    $_SESSION['pinchanged'] = "Your pin was changed successfully";
    header("location:../index.php");
  }else{
    $_SESSION['pinchangedf'] = "Failed to change pin check your account number";
    header("location:../index.php");
  }
}

//ChangePass::changePass($conn,"07108863456");
// ChangePass::mailer("1234");
//$mail = new PHPMailer;

?>
