<?php
session_start();

require_once '../database/db.php';
require_once 'fpdf.php';
//converts number to words
// credit http://www.karlrixon.co.uk/writing/convert-numbers-to-words-with-php/
class NumbersToWords{

    public static $hyphen      = '-';
    public static $conjunction = ' and ';
    public static $separator   = ', ';
    public static $negative    = 'negative ';
    public static $decimal     = ' point ';
    public static $dictionary  = array(
      0                   => 'zero',
      1                   => 'one',
      2                   => 'two',
      3                   => 'three',
      4                   => 'four',
      5                   => 'five',
      6                   => 'six',
      7                   => 'seven',
      8                   => 'eight',
      9                   => 'nine',
      10                  => 'ten',
      11                  => 'eleven',
      12                  => 'twelve',
      13                  => 'thirteen',
      14                  => 'fourteen',
      15                  => 'fifteen',
      16                  => 'sixteen',
      17                  => 'seventeen',
      18                  => 'eighteen',
      19                  => 'nineteen',
      20                  => 'twenty',
      30                  => 'thirty',
      40                  => 'fourty',
      50                  => 'fifty',
      60                  => 'sixty',
      70                  => 'seventy',
      80                  => 'eighty',
      90                  => 'ninety',
      100                 => 'hundred',
      1000                => 'thousand',
      1000000             => 'million',
      1000000000          => 'billion',
      1000000000000       => 'trillion',
      1000000000000000    => 'quadrillion',
      1000000000000000000 => 'quintillion'
    );
    public static function convert($number){
      if (!is_numeric($number) ) return false;
      $string = '';
      switch (true) {
        case $number < 21:
            $string = self::$dictionary[$number];
            break;
        case $number < 100:
            $tens   = ((int) ($number / 10)) * 10;
            $units  = $number % 10;
            $string = self::$dictionary[$tens];
            if ($units) {
                $string .= self::$hyphen . self::$dictionary[$units];
            }
            break;
        case $number < 1000:
            $hundreds  = $number / 100;
            $remainder = $number % 100;
            $string = self::$dictionary[$hundreds] . ' ' . self::$dictionary[100];
            if ($remainder) {
                $string .= self::$conjunction . self::convert($remainder);
            }
            break;
        default:
            $baseUnit = pow(1000, floor(log($number, 1000)));
            $numBaseUnits = (int) ($number / $baseUnit);
            $remainder = $number % $baseUnit;
            $string = self::convert($numBaseUnits) . ' ' . self::$dictionary[$baseUnit];
            if ($remainder) {
                $string .= $remainder < 100 ? self::$conjunction : self::$separator;
                $string .= self::convert($remainder);
            }
            break;
      }
      return $string;
    }
  }


//prints cheque
class PDF extends FPDF{
  function Header()
    {
      // $this->Image('logo.png',10,8,33);
      $this->SetFont('Helvetica','B',15);
      //$this->SetXY(50, 10);
      $this->Cell(0,10,'Day Bank cheque',0,0,'C');
      $this->Ln(20);
     }
     function tablehead($amount,$accno,$ref,$drawee){
       //$this->SetY(0);
       $this->SetX(120);
       $this->Cell(40,7,"account number: " .$drawee,0,1);
       $this->SetX(120);
       $this->Cell(40,7,"Date: " .date('d/m/Y'),0,1);
       $this->Cell(40,7,"reference number :" .$ref,0,1);
       $this->Cell(40,7,"Pay: ".$accno,0,1);
       $this->Cell(40,7,"Amount : ".$amount,0,1);
       $this->Cell(40,7,"Amount in words : " .NumbersToWords::convert($amount),0,1);
       //$this->Cell(40,7," ____________________________________________",0,1);
       $this->LN();
       $this->Cell(40,7,"Sign : _______________",0,1);
       $this->LN();
     }
}

interface AllServices{

  public function getBalance($connection);
  public function deposit($connection,$amount,$accno);
  public function cancelCheque($connection,$ref_no);
  public function issueCheque($connection,$amount,$issueto);
  public function changePass($connection,$oldpass,$newpass);
}
class Services implements AllServices{
  function __construct(){}

//get bank balances
  function getBalance($connection){
    $accno = $_SESSION['accno'];
    $fname = $_SESSION['fname'];
    $lname = $_SESSION['lname'];

    //get balance
    $sql = "SELECT balance FROM _accounts WHERE  accno = :account";
    $statement = $connection->prepare($sql);
    $statement->execute(array(':account'=>$accno));
    $row = $statement->fetchall();

    $balance = "";
    foreach ($row as $key => $value) {
      $balance =  $value['balance'];
    }

     return json_encode(array($fname. " ". $lname,$accno,$balance));//;
  }
  //get balance for service
  static function getServiceBal($connection){
    //get balance
    $accno = $_SESSION['accno'];

    $sql = "SELECT balance FROM _accounts WHERE  accno = :account";
    $statement = $connection->prepare($sql);
    $statement->execute(array(':account'=>$accno));
    $row = $statement->fetchall();

    $balance = "";
    foreach ($row as $key => $value) {
      $balance =  $value['balance'];
    }
    return $balance;
  }

  static function getforAccountsBalance($connection,$accno){
    $sql = "SELECT balance FROM _accounts WHERE  accno = :account";
    $statement = $connection->prepare($sql);
    $statement->execute(array(':account'=>$accno));
    $row = $statement->fetchall();

    $balance = "";
    foreach ($row as $key => $value) {
      $balance =  $value['balance'];
    }
    return $balance;
  }

  //update balances through creditting  and debitting;
  function deposit($connection,$amount,$accno){
    try {
      $connection->beginTransaction();

      $accno = $_SESSION['accno'];
      $sql = "UPDATE _accounts SET balance= :balance WHERE accno = :account";
      $statement = $connection->prepare($sql);


      $final_bal = $amount + Services::getforAccountsBalance($connection,$accno);
      //do not deposit to self
      if($accno ==$_SESSION['accno']){
        return false;
      };
      //update balance
      $statement->execute(array(':balance' => $final_bal,':account'=>$accno));

      if($statement->rowCount()<1){
        return false;
      }
        //update transaction history
        $insert_sql = "INSERT INTO _transactionshistory (accno,date,debit,credit,
          balance) VALUES(:accno,:date,:debit,:credit,:balance)";
        $stmt = $connection->prepare($insert_sql);
        $stmt->execute(array(':accno'=>$accno,':date'=>date("Y/m/d"),':debit'=>0,':credit'=>$amount,':balance'=>$final_bal));
        //$stmt = $connection->prepare($insert_sql);
        // if($stmt->rowCount()<1){
        //   echo "Insert failed";
        // }else{
        //   echo "string";
        // }
      $connection->commit();
        return true;
    } catch (Exception $e) {

      $connection->rollBack();
      return false;
    }

  }

  function issueCheque($connection,$amount,$issueto){
    //debit account

    //refnuber
    $cheq_ref = Services::randomString();
    $balance = Services::getServiceBal($connection);
    $accno = $_SESSION['accno'];
    //$sql = "UPDATE _accounts SET balance= :balance WHERE accno = :account";
    $charges = Services::charges($amount);
    //$stmt = $connection->prepare($sql);
    //$stmt->execute(array(':balance'=>$balance-$amount-$charges,':account'=>$accno));
    //check if balance is sufficient

    //dont issue cheque to cashiers and administrators
    $sql_check = "SELECT isadmin , iscashier FROM _userdetails WHERE accno=:accno";
    $stmt_check = $connection->prepare($sql_check);
    $stmt_check->execute(array(':accno'=>$issueto));
    $check_result = $stmt_check->fetch();
    if($check_result[0] == 1 || $check_result[1]==1){
      return "000000";
    }

    if($amount>140000){
      return "000000";
    }


    if($balance<($amount+$charges)){
      return "000000";
    }
    //update transaction history and assign check number
    $insert_sql = "INSERT INTO _transactionshistory (accno,date,debit,credit,check_ref,
      charges,balance,cheqto,cheq_amount) VALUES(:accno,:date,:debit,:credit,:ref,:charges,:balance,:cheqto,:cheq_amount)";
    $stmt = $connection->prepare($insert_sql);
    $stmt->execute(array(':accno'=>$accno,':date'=>date("Y/m/d"),':debit'=>0,
    ':credit'=>0,':ref'=>$cheq_ref,':charges'=>0,':balance'=>$balance,':cheqto'=>$issueto,':cheq_amount'=>$amount));
    //echo $charges;
    return $cheq_ref;
  }

  function cancelCheque($connection,$ref_no){
    //get issued check number from transaction history
    // $sql = "SELECT check_ref,debit,accno from _transactionshistory WHERE check_ref= :check_ref AND cancelled_cheque=0";
    // $statement = $connection->prepare($sql);
    // $statement->execute(array(':check_ref' => $ref_no));
    // $row = $statement->fetchall();
    // if(sizeof($row)<1){
    //   return false;
    // }
    // $debit;
    // $accno;
    // foreach ($row as $key => $value) {
    //   $debit =  $value['debit'];
    //   $accno =$value['accno'];
    // }

    // $initial = $debit;
    //update balances and mark check as cancelled
    //$accno = $_SESSION['accno'];
    //$update_sql = "UPDATE _accounts SET balance = :balance WHERE accno = :account";
    //$update_statement = $connection->prepare($update_sql);

    //balance as of now
    // $debit = $debit + Services::getServiceBal($connection)-Services::charges($initial);

    //execute UPDATE
    //$update_statement->execute(array(':balance' => $debit,':account'=>$accno));

    //update cheque as cancelled;
    $cancel_update = "UPDATE _transactionshistory SET cancelled_cheque=1 WHERE check_ref= :check_ref AND cancelled_cheque=0 ";
    $cancel_stmt = $connection->prepare($cancel_update);
    $cancel_stmt->execute(array(':check_ref' => $ref_no));

    if($cancel_stmt->rowCount()){
      return true;
    }

    //record the transaction history
    // $history_sql = "INSERT INTO _transactionshistory (accno,date,debit,credit,check_ref,cancelled_cheque,balance) VALUES(:accno,:date,:debit,:credit,:check_ref,:cancelled,:balance)";
    // $history_stmt = $connection->prepare($history_sql);
    // $history_stmt->execute(array(':accno'=>$accno,':date'=>date("Y/m/d"),':debit'=>0,':credit'=>$initial,
    // ':check_ref'=>$ref_no,':cancelled'=>1,':balance'=>$debit));//-Services::charges($initial)

    return false;

  }
  //change password
  function changePass($connection,$oldpass,$newpass){
    $accno = $_SESSION['accno'];
    $sql = "SELECT accno,pin FROM _userdetails WHERE accno=:accno AND pin=:pin";
    $stmt = $connection->prepare($sql);
    $stmt->execute(array(':accno'=>$accno,':pin'=>md5($oldpass)));
    $row = $stmt->fetchall();
    //check if old pass is ok
    //echo md5($oldpass) ." " . md5($newpass) ;
    if(sizeof($row)>0){
      //update to new password
      $update_sql = "UPDATE _userdetails SET pin=:pin WHERE accno=:accno ";
      $update_stmt = $connection->prepare($update_sql);
      $update_stmt->execute(array(':pin'=>md5($newpass),':accno'=>$accno));
      //echo $newpass . "new pass";
      //echo md5($oldpass);
      return true;
    }else{
       return false;
    }

  }

  //calculate withdrawal charges
  static function charges($amount){

            //fetch current charges
            $charges_config = file_get_contents('../rates.json');
            $charges_data = json_decode($charges_config,true);
            //actual data
            $range1 = 0;
            $range2=$range3=$range4=$range5=$range6=$range7=$range8=0;
            foreach ($charges_data as $key => $value) {
              $range1 = $value['0-4999'];
              $range2 = $value['5000-7500'];
              $range3 = $value['75001-10000'];
              $range4 = $value['10001-15000'];
              $range5 = $value['15001-20000'];
              $range6 = $value['20001-35000'];
              $range7 = $value['35001-140000'];
              //$range8 = $value['N/A'];
            }
    if($amount>=5000 && $amount<7501){
      return $range2;
    }elseif($amount>7500 && $amount<10001){
      return $range3;
    }elseif ($amount>10000 && $amount<15001) {
      return $range4;
    }elseif ($amount>15000 && $amount<20001) {
      return $range5;
    }elseif ($amount>20000 && $amount<35001) {
      return $range6;
    }elseif($amount>35000 && $amount<=140000){
      return $range7;
    }else{
      if($amount>140000){
        return "N/A";
      }
      return 0;
    }

  }

  static function randomString($length = 6) {
      $str = "";
      $characters = array_merge(range('A','Z'), range('a','z'), range('0','9'));
      $max = count($characters) - 1;
      for ($i = 0; $i < $length; $i++) {
      	$rand = mt_rand(0, $max);
      	$str .= $characters[$rand];
      }
      return $str;
      }

  //apply for bank administrators
  function payCheq($connection,$cheq_ref){

    //pay cheque
    $sql = "SELECT accno ,debit,cheqto,cheq_amount FROM _transactionshistory WHERE check_ref=:cheq_ref AND cheqpaid=0 ";
    $stmt = $connection->prepare($sql);
    $stmt->execute(array(':cheq_ref'=>$cheq_ref));
    $row = $stmt->fetch();
    // echo $row['debit'];
    if($row['cheq_amount']>0){
      //deduct drawer amount debited + charges and update balance
      try {

        $connection->beginTransaction();
        //balance before debit and charges
        $balance = Services::getforAccountsBalance($connection,$row['accno']);
        $payee_sql = "UPDATE _accounts SET balance= :balance WHERE accno = :account";
        $charges = Services::charges($row['cheq_amount']);
        $stmt = $connection->prepare($payee_sql);
        $stmt->execute(array(':balance'=>$balance-$row['cheq_amount']-$charges,':account'=>$row['accno']));

        //get account to pay to
        $payee_balSQL = "SELECT balance FROM _accounts WHERE accno=:payee_acc";
        $statement_p = $connection->prepare($payee_balSQL);

        $statement_p->execute(array(':payee_acc'=>$row['cheqto']));
        $payee_bal = $statement_p->fetch();

        //update payee account balance
        $update_accounts = "UPDATE _accounts SET balance=:bala WHERE accno=:acc";
        $up_stmt = $connection->prepare($update_accounts);
        $new_bal = $payee_bal['balance']+$row['cheq_amount'];
        $up_stmt->execute(array(':bala'=>$new_bal,':acc'=>$row['cheqto']));

        //record the transaction history
        $history_sql = "INSERT INTO _transactionshistory (accno,date,debit,credit,check_ref,cancelled_cheque,balance) VALUES(:accno,:date,:debit,:credit,:check_ref,:cancelled,:balance)";
        $history_stmt = $connection->prepare($history_sql);
        $history_stmt->execute(array(':accno'=>$row['cheqto'],':date'=>date("Y/m/d"),':debit'=>0,':credit'=>$row['cheq_amount'],
        ':check_ref'=>$cheq_ref,':cancelled'=>1,':balance'=>$new_bal));

        //record drawer transaction details for drawer
        $history_sql2 = "INSERT INTO _transactionshistory (accno,date,debit,credit,check_ref,charges,cancelled_cheque,balance) VALUES(:accno,:date,:debit,:credit,:check_ref,:charges,:cancelled,:balance)";
        $history_stmt2 = $connection->prepare($history_sql2);
        $history_stmt2->execute(array(':accno'=>$row['accno'],':date'=>date("Y/m/d"),':debit'=>$row['cheq_amount'],':credit'=>0,
        ':check_ref'=>$cheq_ref,':charges'=>$charges,':cancelled'=>1,':balance'=>$balance-$row['cheq_amount']-$charges));


        //mark cheque as paid
        $t_sql = "UPDATE _transactionshistory SET cheqpaid=1 WHERE check_ref=:cheq_ref";
        $t_stmt = $connection->prepare($t_sql);
        $t_stmt->execute(array(':cheq_ref'=>$cheq_ref));
        // if($payee_sql->rowCount()<1 && $up_stmt->rowCount()<1 && $history_stmt->rowCount()<1 && $up_stmt->rowCount()<1){
        //   $connection->rollBack();
        //   return false;
        // }
        $connection->commit();

        return true;

      } catch (Exception $e) {
        $connection->rollBack();
        //echo "Failed: " . $e->getMessage();
        return false;
      }
    }

    return false;
  }

  //get unpaid cheques
  static function getUnpaidCheq($connection){
    $sql = "SELECT check_ref from _transactionshistory WHERE cheqpaid=0 AND check_ref != '000000' AND cancelled_cheque=0";
    $t_stmt = $connection->prepare($sql);
    $t_stmt->execute();

    $data = $t_stmt->fetchall(PDO::FETCH_ASSOC);
    $ref = array();
    foreach ($data as $key => $value) {
        $ref[] = $value['check_ref'];
    }
    // if($t_stmt->rowCount()>0){
    //   $rtesult = $t_stmt->fetchall();
    //   //echo $result[0]['check_ref'];
    //   echo json_encode($ref);
    // }
    return json_encode($ref);
  }

  //function assign admin and cashier
  function assignCashier($connection,$accno){
    $sql = "UPDATE _userdetails SET iscashier = 1,isuser= 0 WHERE accno = :accno";
    $statement = $connection->prepare($sql);
    $statement->execute(array(':accno'=>$accno));
    if($statement->rowCount()<1){
      return false;
    }
    return true;
    // $row = $statement->fetchall();
    // if(sizeof($row)>0){
    //   return true;
    // }else {
    //   return false;
    // }
  }

  //function revoke assignCashier
  function revokeCashier($connection,$accno){

    $sql = "UPDATE _userdetails SET iscashier = 0 WHERE accno = :accno";
    $statement = $connection->prepare($sql);
    $statement->execute(array(':accno'=>$accno));

    if($statement->rowCount()<1){
      return false;
    }

    return true;


  }

  static function getAllCashiers($connection){
    //get all cashiers
    $sql_cashiers = "SELECT * FROM _userdetails WHERE iscashier=1";
    $stmt_cashiers = $connection->prepare($sql_cashiers);
    $stmt_cashiers ->execute();

    $data = $stmt_cashiers ->fetchall(PDO::FETCH_ASSOC);
    //echo $data;
    return json_encode($data);
  }

  //function get all accounts that are not cashiers
  static function getAllAccounts($connection){
    $sql = "SELECT firstname,lastname,accno FROM _userdetails WHERE iscashier=0";
    $statement = $connection->prepare($sql);
    $statement->execute();
    $data = $statement->fetchall();
    echo json_encode($data);
  }

  static function editCharges(){
    $charges_config = file_get_contents('e:\rates.json');
    //$charges_data = json_decode($charges_config,true);
    //actual data
    // $range1 = 0;
    // $range2=$range3=$range4=$range5=$range6=$range7=$range8=0;
    // foreach ($charges_data as $key => $value) {
    //   $range1 = $value['0-4999'];
    //   $range2 = $value['5000-7500'];
    //   $range3 = $value['75001-10000'];
    //   $range4 = $value['10001-15000'];
    //   $range5 = $value['15001-20000'];
    //   $range6 = $value['20001-35000'];
    //   $range7 = $value['35001-140000'];
    //   //$range8 = $value['N/A'];
    // }

    return json_encode($charges);
  }
}


//$src = new Services();
//$src->getAllAccounts($conn);
//Services::getAllCashiers($conn);

//$src->payCheq($conn,"nF46Bd");
//$src->deposit($conn,2500,"0710883976");
//$src->cancelCheque($conn,'zZsaoh');
//$src->changePass($conn,"1111","1111");

//$src->issueCheque($conn,"1000","098767898");
//echo $src->randomString();
//$src->getBalance($conn)
//Services::getUnpaidCheq($conn);
//Services::charges(6000);
?>
