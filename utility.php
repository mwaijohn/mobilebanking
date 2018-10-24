<?php //session_destroy() ?>
<?php session_start(); ?>
<?php if(!(isset($_SESSION['accno']))){ header("location:index.php");} ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Utility services</title>
    <link rel="stylesheet" href="./css/style.css">
</head>

<body>
    <div id="header" class="header">
      <img class="logo" src="logo.png" alt="Mobile bank" height="80px"/>
      <p class="title">Self <span>Service Portal</span></p>
        <!-- <p>ONLINE BANKING SELF SERVICE PORTAL</p> -->
        <i class="fa fa-money" aria-hidden="true"></i>
    </div>

    <div class="service-container">
        <div class="service-btn">
            <?php if(!($_SESSION['isadmin'] || $_SESSION['iscashier'])) {
              echo '<button type="submit" id="bal" name="bal">Check balance</button>';
            }?>
            <?php if($_SESSION['iscashier'] == 1 || $_SESSION['isadmin']==1 ){
              echo '<button type="submit" id="deposit">Deposit<i class="fa fa-money"></i></button>';
            } ?>
            <?php if($_SESSION['isuser']==1){
              echo '<button type="submit" id="issuecheq">Issue cheque</button>';
            } ?>
            <?php if($_SESSION['iscashier'] == 1 || $_SESSION['isadmin']==1){
              echo '<button type="submit" id="ppcheq">Pay cheque</button>';
            } ?>
            <?php if($_SESSION['iscashier'] == 1 || $_SESSION['isadmin']==1){
              echo '<button type="submit" id="ccheq">Cancel cheque</button>';
            } ?>
            <?php if($_SESSION['isadmin']==1){
              echo "<form action='../banking/rates.php'><button style= 'width:100%' type='submit'>Update rates</button></form>";
            }?>
            <?php if($_SESSION['iscashier'] == 1 || $_SESSION['isadmin']==1){
              echo "<form action='../banking/accounts/signup.php'><button style= 'width:100%' type='submit' id='reguser'>Register User</button></form>";
              //echo '<a class href="../banking/accounts/signup.php"><button type="submit" id="reguser">Register User</button></a>';
            } ?>
            <?php if($_SESSION['isadmin']==1){
              echo '<button type="submit" id="assigncashier">Assign Cashier</button>';
            } ?>
            <?php if($_SESSION['isadmin']==1){
              echo '<button type="submit" id="revokecashier">Revoke Cashier</button>';
            } ?>
            <button type="submit" id="change-pin">Change Pin</button>
            <button type="submit"  id="history">reports</button>
            <form action='../banking/utility.php' method="post"><input type='hidden' name="logout" value="logout"/><button style= 'width:100%' type='submit' id='logout'>Log out</button></form>
            <script>document.querySelector('#history').addEventListener('click',
            function(e){
              e.preventDefault();
              // window.open( '../banking/reports/render_reports.php' );
              window.location = '../banking/reports/render_reports.php';
            }
          )</script>
        </div>
        <?php if(isset($_POST['logout'])){
          session_unset();
          session_destroy();
          header("location:utility.php");
        }
        ?>
        <div class="working">
            <!-- <input type="number" style='width:50%;margin-top:15px;' id="deposit" value="google" name="deposit"
                placeholder="Enter amount" required>
            <input type="button" style='width:50%' value="Deposit" id="sure-deposit"> -->
            <strong><?php if(isset($_SESSION['massage'])){echo $_SESSION['massage'];}?></strong>
        </div>
    </div>
    <script src="./js/jquery.min.js" charset="utf-8"></script>
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script> -->
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="./js/service.js"></script>
</body>

</html>
