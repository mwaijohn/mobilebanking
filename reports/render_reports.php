<?php
require_once 'reports.php';
require_once '../database/db.php';
require_once 'pdfs.php'
 ?>
<?php session_start(); ?>
<?php if(!(isset($_SESSION['accno']))){ header("location:../index.php");} ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>customer summary reports</title>
  <link rel="stylesheet" href="../css/report.css">
  <style media="screen">
    .tr {
      border-collapse: collapse;
      width: 80%;
      margin: auto;
    }

    th,
    td {
      text-align: left;
      padding: 8px;
    }

    tr:nth-child(odd) {
      background-color: #f2f2f2
    }

    .rform {
      align-items: center;
      text-align: center;
    }
  </style>
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.1/css/all.css" integrity="sha384-5sAR7xN1Nv6T6+dT2mhtzEpVJvfS3NScPQTrOxhwjIuvcA67KV2R5Jz6kr4abQsz"
    crossorigin="anonymous">
</head>

<body>
  <div class="header">
    <img class="logo" src="logo.png" alt="Mobile bank" height="80px"/>
        <p class="title">Self <span>Service Portal</span></p>
  </div>
  <div class="reports">
    <h2>Check for all reports</h2><br><br>
    <a href="../"><i class="fa fa-chevron-circle-left"></i>Home</a>
    <a href="#" id="allrep">view all transactions report</a>
    <a href="#" id="debrep">view debit report</a>
    <a href="#" id="credrep">view credit reports</a>
    <a href="#" id="cheqrep">view cheque reports</a>
    <?php if($_SESSION['isadmin']==1){
        echo '<a href="#" id="bankrep">Bank report</a>';
      } ?>
  </div>
  <div class="rform">

    <?php
    if(isset($_POST['startdate']) && isset($_POST['enddate'])){

      //store the dates
      $_SESSION['startdate']= $_POST['startdate'];
      $_SESSION['enddate']= $_POST['enddate'];
            //echo "form submitted";
      $rps = new Reports();
      $data = json_decode($rps->customerReport($conn,$_POST['startdate'],$_POST['enddate']),
      true );
      //echo $data[76];
      $tot_debit = 0;
      $tot_credit = 0;
      $tot_charges = 0;
      echo "<strong >Transactions report for"."   " .
      $_POST['startdate']."  ". "to" ."  ". $_POST['enddate']."</strong><br><br>" ;
      echo "<table class='tr'><tr><th>date</th><th>debit</th><th>credit</th><th>charges</th><th>balance</th></tr>";
      foreach ($data as $key => $record) { // This will search in the 2 jsons
        echo "<tr><td>".date('Y-m-d', strtotime($record['date']))."</td><td>".
        $record['debit']."</td><td>".$record['credit']."</td><td>".$record['charges']."</td><td>".
        $record['balance']."</td></tr>";
        $tot_debit += $record['debit'];
        $tot_credit += $record['credit'];
        $tot_charges += $record['charges'];
        }
        echo "<tr><td><strong>totals</strong></td><td><strong>".$tot_debit.
         "</strong></td><td><strong>" . $tot_credit.
        "</strong></td><td><strong>". $tot_charges ."</strong></td></tr>";
        echo "</table><br>";
        echo "<form action='render_reports.php' method='post' target='_blank'><input type='hidden' id='pactr' name='pactr' value='Print pdf'>
        <input type='submit'  value='Print pdf'></form>";

    }

    //GET credit REPORT
    if(isset($_POST['creports']) && isset($_POST['creporte'])){
      $rps = new Reports();
      $data = json_decode($rps->depositReport($conn,$_POST['creports'],$_POST['creporte']),true );
      $tot_credit = 0;
      $tot_charges = 0;

      echo "<strong>Credit report for"."   " .$_POST['creports']."  ". "to" ."  ". $_POST['creporte']."</strong><br><br>" ;
      echo "<table class='tr' style='width:60%'><tr><th>date</th><th>credit</th></tr>";
      foreach ($data as $key => $record) { // This will search in the 2 jsons
        //$tot_debit += $record['debit'];
        if($record['credit']>0){
            echo "<tr><td>".$record['date']."</td><td>".$record['credit']."</td></tr>";
        }
        $tot_credit += $record['credit'];
        $tot_charges += $record['charges'];
        }
        echo "<tr><td><strong>totals deposit(Kshs)</strong></td><td><strong>" . $tot_credit.
        "</strong></td></tr>";
        echo "</table><br>";
        //echo "<form action='render_reports.php'><input type='hidden' id='crps' name='crps' value='Print PDF'><input type='submit' value='print pdf'></form>";
    }

    //get debit report
    if(isset($_POST['dreports']) && isset($_POST['dreporte'])){
      $rps = new Reports();
      $data = json_decode($rps->depositReport($conn,$_POST['dreports'],$_POST['dreporte']),
       true );
      $tot_debit = 0;
      $tot_charges = 0;

      echo "<strong>Debit report for"."   " .$_POST['dreports']."  ".
       "to" ."  ". $_POST['dreporte'] ."</strong><br><br>";
      echo "<table class='tr'><tr><th>date</th><th>debit</th><th>charges</th></tr>";
      foreach ($data as $key => $record) {

        if($record['debit']>0){
          echo "<tr><td>".date('Y-m-d', strtotime($record['date']))."</td><td>".$record['debit']."</td><td>"
          .$record['charges']."</td></tr>";
        }
        //$tot_debit += $record['debit'];
        $tot_debit += $record['debit'];
        $tot_charges += $record['charges'];
        }
        echo "<tr><td><strong>totals(Kshs)</strong></td><td><strong>" . $tot_debit.
        "</strong></td><td><strong>". $tot_charges ."</strong></td></tr>";
        echo "</table><br>";
        //echo "<form action='render_reports.php'><input type='submit' id='crps' name='crps' value='Print PDF'></form>";
      }

        //all bank Reports
        if(isset($_POST['breports']) && isset($_POST['breporte'])){
          //store the dates
          $_SESSION['startdate']= $_POST['breports'];
          $_SESSION['enddate']= $_POST['breporte'];
          //echo "form submitted";
          $rps = new Reports();
          $data = json_decode($rps->allTransactions($conn,$_POST['breports'],$_POST['breporte']),
          true );
          //echo $data[76];
          $tot_debit = 0;
          $tot_credit = 0;
          $tot_charges = 0;
          echo "<strong>General bank transactions report for"."   " .
          $_POST['breports']."  ". "to" ."  ". $_POST['breporte']."</strong><br><br>" ;
          echo "<table class='tr'><tr><th>date</td><th>account no</th><th>debit</th><th>credit</th><th>charges</th></tr>";
          foreach ($data as $key => $record) {
            echo "<tr><td>".date('Y-m-d', strtotime($record['date']))."</td><td>".$record['accno']."</td><td>" .
            $record['debit']."</td><td>".$record['credit']."</td><td>".$record['charges']."</td></tr>";
            $tot_debit += $record['debit'];
            $tot_credit += $record['credit'];
            $tot_charges += $record['charges'];
            }
            echo "<tr><td><strong>totals</strong></td><td><strong></strong></td><td><strong>".$tot_debit.
             "</strong></td><td><strong>" . $tot_credit.
            "</strong></td><td><strong>". $tot_charges ."</strong></td></tr>";
            echo "</table><br>";
            echo "<form action='render_reports.php' method='post' target='_blank'><input type='hidden' id='abrt' name='abrt' value='abrt'><input type='submit' id='crps' name='crps' value='print pdf'></form>";
        }

        //cheque reports
        if(isset($_POST['chreps'])){
          $rps = new Reports();
          $data = json_decode($rps->chequeReport($conn,$_POST['chreps'],$_POST['chrepe']),true);
          echo "<strong>General bank transactions report for"."   " .
          $_POST['chreps']."  ". "to" ."  ". $_POST['chrepe']."</strong><br><br>" ;
          echo "<table class='tr'><tr><th>date</td><th>account no</th><th>cheque number</th><th>paid/cancelled</th><th>amount</th></tr>";
          $cpaid;
          foreach ($data as $key => $record) {
            if($record['cheqpaid']=='1'){
              $cpaid = "paid";
            }else{
              $cpaid = "Cancelled";
            }
            echo "<tr><td>".date('Y-m-d', strtotime($record['date']))."</td><td>".$record['cheqto']."</td><td>" .
            $record['check_ref']."</td><td>".$cpaid."</td><td>".$record['cheq_amount']."</td></tr>";
            }
            // echo "<tr><td><strong>totals</strong></td><td><strong></strong></td><td><strong>".$tot_debit.
            //  "</strong></td><td><strong>" . $tot_credit.
            // "</strong></td><td><strong>". $tot_charges ."</strong></td></tr>";
            echo "</table><br>";
            //echo "<form action='render_reports.php' method='post'><input type='hidden' id='abrt' name='abrt' value='abrt'><input type='submit' id='crps' name='crps' value='print pdf'></form>";

        }

        //pdf Reports

        //customer all transactions report
        if(isset($_POST['pactr'])){
        $pdf=new ReportPDF();
        $rps = new Reports();
        //get opening balance
        $opening_balance = Reports::getOpeningBalances($conn,$_SESSION['accno'],$_SESSION['startdate']);
        $data = json_decode($rps->customerReport($conn,$_SESSION['startdate'],$_SESSION['enddate']),true);
        $pdf->AddPage();
        $pdf->SetFont('Arial','',14);
        $pdf->tablehead($_SESSION['fname']." ". $_SESSION['lname'],$_SESSION['accno'],$_SESSION['startdate'],$_SESSION['enddate'],$opening_balance);
        $pdf->allCustomerStatement($data);
        //$pdf->bankR($data);
        $pdf->Output();
        }

        //all bank transactions report
        if(isset($_POST['abrt'])){
          $rps = new Reports();
          $data = json_decode($rps->allTransactions($conn,$_SESSION['startdate'],$_SESSION['enddate']),true);
          $pdf=new ReportPDF();
          $pdf->AddPage();
          $pdf->SetFont('Arial','',14);
          //$pdf->tablehead($_SESSION['fname']." ". $_SESSION['lname'],$_SESSION['accno'],$_SESSION['startdate'],$_SESSION['enddate']);
          $pdf->bankHeader($_SESSION['startdate'],$_SESSION['enddate']);
          $pdf->bankR($data);
          //$pdf->bankR($data);
          $pdf->Output();
        }
     ?>
  </div>
  <script src="../js/jquery.min.js" charset="utf-8"></script>
  <script src="../js/jquery-ui.min.js" charset="utf-8"></script>
  <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="http://code.jquery.com/ui/1.11.0/jquery-ui.js"></script> -->
  <script src='../js/report.js'></script>
</body>

</html>
