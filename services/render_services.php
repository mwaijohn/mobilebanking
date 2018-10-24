<?php
require_once '../database/db.php';
require_once 'services.php';

if(isset($_POST['bal'])){
  $src = new Services();
  echo $src->getBalance($conn);
}


if(isset($_POST['deposit'])){
  $amount = $_POST['deposit'];
  $payto = $_POST['amount'];
  $src = new Services();

  if($src->deposit($conn,$amount,$payto)){
    echo json_encode(array('success'));
  }else{
    echo json_encode(array('fail'));
  }
}

//cancel cheque
if(isset($_POST['ref'])){
  $ref = $_POST['ref'];
  //$acheq = $_POST['acheq'];
  $src = new Services();
  if($src->cancelCheque($conn,$ref)==true){
    echo json_encode(array('success'));
  }else{
    echo json_encode(array('fail'));
  }
}

//issue cheque
if(isset($_POST['pcheq'])  && isset($_POST['pcamount'])){
  $src = new Services();
  if(Services::getServiceBal($conn)>$_POST['pcamount']){
    $cheq_ref = $src->issueCheque($conn,$_POST['pcamount'],$_POST['pcheq']);
    //store ref and account in session
    $_SESSION['icheq_ref'] = $cheq_ref;
    $_SESSION['cheqamount'] = $_POST['pcamount'];
    $_SESSION['payto'] = $_POST['pcheq'];

    if($cheq_ref ==="000000"){
      echo json_encode(array('fail'));
    }else{
      echo json_encode(array('success'));
    }
  }else{
    echo json_encode(array('fail'));
  }
}

//print cheque
if(isset($_POST['printcheq'])){
  $cheque = new PDF();
  $cheque->AddPage();
  $cheque->SetFont('Helvetica','',14);
  $cheque->tablehead($_SESSION['cheqamount'],$_SESSION['payto'],$_SESSION['icheq_ref'],$_SESSION['accno']);
  $cheque->Output('D',$_SESSION['icheq_ref'].'.pdf');
}
//change password
if(isset($_POST['newpass']) && isset($_POST['oldpass'])){
  $newpass = $_POST['newpass'];
  $oldpass = $_POST['oldpass'];
  $src = new Services();
  if($src->changePass($conn,$oldpass,$newpass)==true){
    //echo md5($oldpass) ." " . md5($newpass) ;
    echo json_encode(array('success'));
  }else{
    echo json_encode(array('fail'));
  }
}

//full fill cheques
if(isset($_POST['ppcheq'])){
  echo Services::getUnpaidCheq($conn);
  //echo "received data";
}
//pay cheque
if(isset($_POST['refnoo'])){
  $src = new Services();
  $cheqref = $_POST['refnoo'];

  if($src->payCheq($conn,$cheqref)==true){
    echo json_encode(array("success"));
  }else{
    echo json_encode(array("fail"));
  }
}

//get accounts
if(isset($_POST['accounts'])){
    $src = new Services();
  echo $src->getAllAccounts($conn);
}

//asign cashiers
if(isset($_POST['makecashier'])){
  $account = $_POST['account'];
  //Services::assignCashier($conn,$account);
  //echo "data received";
  if(Services::assignCashier($conn,$account)==true){
    //echo json_encode(array("success"));
    $_SESSION['massage'] = "Assigned Cashier ". $account;
    header("location:../utility.php");
  }else{
    $_SESSION['massage'] = "Assigning cashier ". $account . " failed";
    //echo json_encode(array("fail"));
    header("location:../utility.php");
  }
}

//revoke cashier get ll cashiers
if(isset($_POST['revaccounts'])){
  //$account = $_POST['rev-account'];
  echo Services::getAllCashiers($conn);
}

if(isset($_POST['revokecashier'])){
  $src = new Services();
  $accno = $_POST['account'];
  if($src->revokeCashier($conn,$accno) == true){
    $_SESSION['massage'] = "Revoked cashier ". $accno;
    header("location:../utility.php");
  }else{
    $_SESSION['massage'] = "Revoking cashier ". $account . " failed";
    header("location:../utility.php");
    //echo json_encode(array("fail"));
  }
}

 ?>
