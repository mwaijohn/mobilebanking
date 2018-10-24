<?php
//require_once '../database/db.php';
// require_once 'create_account.php';

class RegisterUser{

    private $fname,$lname,$pin;
    private $accno,$email;
    //database connection
    private $connection;
    function __construct($fname,$lname,$accno,$pin,$email){
        $this->fname = $fname;
        $this->lname = $lname;
        $this->accno = $accno;
        $this->pin = $pin;
        $this->email = $email;
    }
    //register user if not registered
    function register($connection,$registeredby){

        $sql = "SELECT * FROM _userdetails WHERE  accno = :account ";
        $statement = $connection->prepare($sql);
        try{
          $statement->execute(array(':account'=>$this->accno));
          $row = $statement->fetchall();
          if(sizeof($row)<1){
            $insert_sql = "INSERT INTO _userdetails (datejoined,firstname,lastname,accno,email,pin,registeredby) VALUES(:date,:fname,:lname,:accno,:email,:pin,:register)";
            $stmt = $connection->prepare($insert_sql);
            $stmt->execute(array('date'=>date("Y/m/d"),':fname'=>$this->fname,':lname'=>$this->lname,
            ':accno'=>$this->accno,':email'=>$this->email,':pin'=>md5($this->pin),':register'=>$registeredby));
            echo "user registered";
            return true;
          }

        }catch(PDOException $e){
          return false;
        }
    }
}

/**
 * login and assign session to users
 */
class Login
{
  private $pin,$accno;
  function __construct($pin,$accno)
  {
    $this->pin = $pin;
    $this->accno = $accno;
  }

  function logUser($connection){
    echo "string";
    $sql = "SELECT firstname,lastname,isadmin,iscashier,isuser FROM _userdetails WHERE  accno = :account AND pin = :pin ";
    $statement = $connection->prepare($sql);
    $statement->execute(array(':account'=>$this->accno,':pin'=>md5($this->pin)));
    $row = $statement->fetchall();
    if(sizeof($row)<1){
      //session_start();
      $_SESSION['error'] = "Your details are wrong";
      return false;
    }else{
      //session_start();
      //get first and last name
      $fname = "";
      $lname = "";
      $isadmin = "";
      $iscashier = "";
      $isuser = "";
      $accs = $this->accno;

      foreach ($row as $key => $value) {
        $fname = $value['firstname'];
        $lname = $value['lastname'];
        $isadmin = $value['isadmin'];
        $iscashier = $value['iscashier'];
        $isuser = $value['isuser'];
      }
      $_SESSION['fname'] = $fname;
      $_SESSION['lname'] = $lname;
      $_SESSION['isadmin'] = $isadmin;
      $_SESSION['iscashier'] = $iscashier;
      $_SESSION['isuser'] = $isuser;
      //$_SESSION['accno'] = $accs;

      return true;
    }

  }
}

// $reg = new RegisterUser("conrad","mwangi","999875767363",md5(1111));
//$reg->register($conn);
?>
