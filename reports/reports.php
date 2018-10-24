<?php

require_once '../database/db.php';
//session_start();
class Reports{
  function __construct(){}
  function customerReport($connection,$start_date,$end_date){
    $accno = $_SESSION['accno'];
    $sql = "SELECT * FROM _transactionshistory WHERE (date  BETWEEN :sdate AND :edate) AND accno=:accno AND (debit+ credit) !=0";
    $statement = $connection->prepare($sql);
    $statement->execute(array(':sdate'=>$start_date,':edate'=>$end_date,':accno'=>$accno));

    //$row = $statement->fetch(PDO::FETCH_ASSOC);
    $data = array();
    while($row = $statement->fetch(PDO::FETCH_ASSOC)){
      $data[] = $row;
    }

    return json_encode($data);
  }
  //credit and debit report
  function depositReport($connection,$start_date,$end_date){
    $accno = $_SESSION['accno'];
    $sql = "SELECT date,credit,debit,balance,charges FROM _transactionshistory WHERE (date  BETWEEN :sdate AND :edate) AND accno=:accno";
    $statement = $connection->prepare($sql);
    $statement->execute(array(':sdate'=>$start_date,':edate'=>$end_date,':accno'=>$accno));

    //$row = $statement->fetch(PDO::FETCH_ASSOC);
    $data = array();
    while($row = $statement->fetch(PDO::FETCH_ASSOC)){
      $data[] = $row;
    }

    return json_encode($data);
  }
  function canceledChequesR($connection,$start_date,$end_date){
    $accno = "07108863456"; // $_SESSION['accno'];
    $sql = "SELECT date,credit,balance FROM _transactionshistory WHERE (date  BETWEEN :sdate AND :edate) AND accno=:accno";
    $statement = $connection->prepare($sql);
    $statement->execute(array(':sdate'=>$start_date,':edate'=>$end_date,':accno'=>$accno));

    //$row = $statement->fetch(PDO::FETCH_ASSOC);
    $data = array();
    while($row = $statement->fetch(PDO::FETCH_ASSOC)){
      $data[] = $row;
    }

    echo json_encode($data);
  }

  //gets all bank transactions
  function allTransactions($connection,$start_date,$end_date){
    $sql = "SELECT * FROM _transactionshistory WHERE (date  BETWEEN :sdate AND :edate) AND (credit+debit) != 0";
    $statement = $connection->prepare($sql);
    $statement->execute(array(':sdate'=>$start_date,':edate'=>$end_date));

    //$row = $statement->fetch(PDO::FETCH_ASSOC);
    $data = array();
    while($row = $statement->fetch(PDO::FETCH_ASSOC)){
      $data[] = $row;
    }

    return json_encode($data);
  }

  function chequeReport($connection,$start_date,$end_date){
    $sql = "SELECT * FROM _transactionshistory WHERE (date  BETWEEN :sdate AND :edate) AND check_ref != '000000' AND cheqto != ' ' AND accno = :accno";
    $statement = $connection->prepare($sql);
    $statement->execute(array(':sdate'=>$start_date,':edate'=>$end_date,':accno'=>$_SESSION['accno']));
    $result = json_encode($statement->fetchall(PDO::FETCH_ASSOC));
    return $result;
  }

  //function returns the opening balance before the report start dates
  static function getOpeningBalances($connection,$accounnumber,$datebefore){
    $sql = "SELECT * FROM _transactionshistory WHERE date<:beforedate AND accno = :accno ORDER BY date DESC";
    $statement = $connection->prepare($sql);
    $statement->execute(array(':beforedate'=>$datebefore,':accno'=>$accounnumber));
    $result = json_decode(json_encode($statement->fetch(PDO::FETCH_ASSOC)));
    echo  $result->balance;
    return $result->balance;
  }
}

//$rps = new Reports();
//$rps->customerReport($conn,"2018-10-01","2018-10-04");
//$rps->depositReport($conn,"2018-10-01","2018-10-04");
//$rps->canceledChequesR($conn,"2018-10-01","2018-10-04");

//Reports::getOpeningBalances($conn,"980890098098","2018-10-14");
//$rps->chequeReport($conn);
 ?>
