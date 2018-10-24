<?php
require 'fpdf.php';
//require 'reports.php';
require_once '../database/db.php';
class ReportPDF extends FPDF{
  function Header()
    {
      $this->Image('logo.png',75,15);
      $this->Ln(35);
      $this->SetFont('Helvetica','B',15);
      //$this->SetXY(50, 10);
      $this->Cell(0,10,'Bank statement',0,0,'C');
      $this->Ln(20);
     }

  function Footer()
    {
      $this->SetXY(100,-15);
      $this->SetFont('Helvetica','I',10);
      $this->Write (5, 'end of page'.$this->PageNo());
    }

    function allCustomerStatement($data){
      //$this->SetY(0);
      $this->SetFont('Helvetica','',12);
      $header = ['date', 'debit','credit','charges','balance'];
      //$this->SetY(0);
      // $this->SetFont('Arial','',12);
      foreach ($header as $key => $value) {
        $this->SetFont('Helvetica','B',12);
        $this->Cell(35,7,$value,1);
        // code...
      }
      $this->LN();
      $tot_debit = 0;
      $tot_credit = 0;
      $tot_charges = 0;
      $this->SetFont('Helvetica','',12);
      foreach ($data as $value) {
        $this->Cell(35,7,date('Y-m-d', strtotime($value['date'])),1);
        $this->Cell(35,7,$value['debit'],1);
        $this->Cell(35,7,$value['credit'],1);
        $this->Cell(35,7,$value['charges'],1);
        $this->Cell(35,7,$value['balance'],1,1);

        $tot_debit += $value['debit'];
        $tot_credit += $value['credit'];
        $tot_charges += $value['charges'];
      }

      $this->SetFont('Helvetica','B',12);
      $this->Cell(35,7,"Total",1);
      $this->Cell(35,7,$tot_debit,1);
      $this->Cell(35,7,$tot_credit,1);
      $this->Cell(35,7,$tot_charges,1);
    }

    //bank debit reports
    function debitR($data){
      $header = ['date', 'debit','charges'];
      //$this->SetY(0);
      $this->SetFont('Arial','',12);
      foreach ($header as $key => $value) {
        $this->Cell(35,7,$value,1);  // code...
      }
      $this->LN();
      $tot_debit = 0;
      $tot_credit = 0;
      $tot_charges = 0;
      foreach ($data as $value) {
        $this->Cell(35,7,$value['date'],1);
        $this->Cell(35,7,$value['debit'],1);
        $this->Cell(35,7,$value['charges'],1,1);

        $tot_debit += $value['debit'];
        $tot_charges += $value['charges'];
      }

      $this->Cell(35,7,"Total",1);
      $this->Cell(35,7,$tot_debit,1);
      $this->Cell(35,7,$tot_charges,1);
    }

    //all bank transactions reports

    function bankR($data){

      $header = ['date','account no','debit','credit','charges'];
      foreach ($header as $key => $value) {
        $this->SetFont('Arial','B',12);
        $this->Cell(35,7,$value,1);  // code...
      }
      $this->SetFont('Arial','',12);
      $this->LN();
      $tot_debit = 0;
      $tot_credit = 0;
      $tot_charges = 0;
      foreach ($data as $value) {
        $this->Cell(35,7,date('Y-m-d', strtotime($value['date'])),1);
        $this->Cell(35,7,$value['accno'],1);
        $this->Cell(35,7,$value['debit'],1);
        $this->Cell(35,7,$value['credit'],1);
        $this->Cell(35,7,$value['charges'],1,1);
        //$this->Cell(35,7,$value['balance'],1,1);

        $tot_debit += $value['debit'];
        $tot_credit += $value['credit'];
        $tot_debit += $value['debit'];
        $tot_charges += $value['charges'];
      }
      $this->SetFont('Helvetica','B',12);
      $this->Cell(35,7,"Total",1);
      $this->Cell(35,7,"",1);
      $this->Cell(35,7,$tot_debit,1);
      $this->Cell(35,7,$tot_credit,1);
      $this->Cell(35,7,$tot_charges,1);
    }

    function tablehead($name,$accno,$sdate,$edate,$opening_balance){
      //$this->SetY(0);
      $this->Cell(40,7,"Name :" .$name,0,1);
      $this->Cell(40,7,"Account number: ".$accno,0,1);
      $this->Cell(40,7,"Statement period: ".$sdate."  - ".$edate ,0,1);

      if($opening_balance){
        $this->Cell(40,7,"Opening Balance: ".$opening_balance,0,1);
      }else{
        $this->Cell(40,7,"Opening Balance: "."0.00",0,1);
      }
      $this->LN();
    }

    function bankHeader($sdate,$edate){
      $this->Cell(40,7,"Name : Day Bank",0,1);
      //$this->Cell(40,7,"Account number: ".$accno,0,1);
      $this->Cell(40,7,"Statement period: ".$sdate."  - ".$edate ,0,1);
      $this->LN();
    }
}

//$pdf=new ReportPDF();
//$rps = new Reports();
//$data = json_decode($rps->allTransactions($conn,"2018-10-01","2018-10-13"),true);
//$data = json_decode($rps-> depositReport($conn,"2018-10-01","2018-10-13"),true);
//$data = json_decode($rps->customerReport($conn,"2018-10-01","2018-10-13"),true);

//echo $data;
//table header
//$pdf->AddPage();
//$pdf->SetFont('Arial','',14);
//$pdf->printtablehead($data);
//$pdf->debitR($data);
//$pdf->bankR($data);
// $pdf->Output();
 ?>
