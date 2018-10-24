<?php
class CreateAccount{
  var $fname,$lname,$accno,$balance=0;
  function __construct($fname,$lname,$accno,$balance){
      $this->fname = $fname;
      $this->lname = $lname;
      $this->accno = $accno;
      $this->balance = $balance;
  }

  //insert data to accounts database;
  function saveAccount($connection){
    $insert_sql = "INSERT INTO _accounts (firstname,lastname,accno,balance) VALUES(:fname,:lname,:accno,:balance)";
    try{
      $stmt = $connection->prepare($insert_sql);
      $stmt->execute(array(':fname'=>$this->fname,':lname'=>$this->lname,':accno'=>$this->accno,':balance'=>$this->balance));
      //echo "23333";
      return true;
    }catch(PDOException $e){
      echo $e->getMessage();
      return false;
    }
  }
}
?>
