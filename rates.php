<?php session_start(); ?>
<?php if(!(isset($_SESSION['accno']) && isset($_SESSION['isadmin']))){ header("location:index.php");} ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Document</title>
  <link rel="stylesheet" href="./css/rates.css">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.1/css/all.css" integrity="sha384-5sAR7xN1Nv6T6+dT2mhtzEpVJvfS3NScPQTrOxhwjIuvcA67KV2R5Jz6kr4abQsz"
    crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

</head>

<body>
  <div id="header" class="header">
    <img class="logo" src="logo.png" alt="Mobile bank" height="80px" />
    <p class="title">Self <span>Service Portal</span></p>
    <!-- <p>ONLINE BANKING SELF SERVICE PORTAL</p> -->
  </div>
  <div class="work-area">
          <p><strong>Edit charges</strong></p>
          <?php if(isset( $_SESSION['rateupdatesuccess'])){echo '<div class="success" ><small class="error" style="color:green;">'.$_SESSION['rateupdatesuccess'].'</small></div><br>';}else{echo "";} unset($_SESSION['rateupdatesuccess']);?>
          <?php
          $charges_config = file_get_contents('rates.json');
          $charges_data = json_decode($charges_config,true);
          //actual data
          $range1 = 0;
          $range2=$range3=$range4=$range5=$range6=$range7=$range8=0;
          echo '<div class="card">
            <form class="rates-form" method="post">';
            // print(sizeof($charges_data[0]));
          foreach ($charges_data[0] as $key=>$value) {
            echo '<input type="text" name="'.$key.'" disabled id="'.$key.'" value=' .$key .'>';
            echo '&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<input type="number" required name="'.$key.'"'. $value.' value='.(int)$value.' ><br><br>'; //.$range2.;
          }
            echo '<input type="hidden" name="ghdhghg" value="values"><button type="submit" value="Save">Update rates</button></form>
            <br>
          <a href="."><i class="fa fa-chevron-circle-left"></i>Home</a>
          </div>';
    ?>
    <?php
    //update rates
    if(isset($_POST['ghdhghg'])){
      //save new charges
      $data = array_pop($_POST);
      $json = json_encode(array($_POST));
      try {
        file_put_contents('rates.json',$json);
        $_SESSION['rateupdatesuccess'] = "You have updated rates successifully";
        header('location:rates.php');
      } catch (Exception $e) {
        $_SESSION['rateupdatesuccess'] = "Failed to update rates " . $e->getMAssage();
      }
    }
     ?>
  </div>
  <script src="./js/jquery.min.js" charset="utf-8"></script>
  <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script> -->
  <script src="./js/rates.js"></script>
</body>

</html>
