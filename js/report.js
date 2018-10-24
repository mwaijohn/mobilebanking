$(document).ready(function () {
  let form = `
  <form method="post" action="./render_reports.php">
    <input type="text" name="startdate" id="stdt" placeholder="start date"/>
    <input type="text" name="enddate" id="eddt" placeholder="end date"/>
    <input type="submit"  placeholder="start date"/>
  </form>
  `;
  $("#allrep").click(function () {
    $(".rform").html($(form));
    //$(".rform").css({"display:none"});
    $(".tr").html($());
  });
});


$(document).ready(function () {
  let form = `
  <form method="post" action="./render_reports.php">
    <input type="text" name="dreports" id="dreports" placeholder="start date"/>
    <input type="text" name="dreporte" id="dreporte" placeholder="end date"/>
    <input type="submit" name="startdate" placeholder="start date"/>
  </form>
  `;
  $("#debrep").click(function () {
    $(".rform").html($(form));
    $(".tr").html($());
  });
});

$(document).ready(function () {
  let form = `
  <form method="post" action="./render_reports.php">
    <input type="texts" name="creports" id="creports" placeholder="start date"/>
    <input type="text" name="creporte" id="creporte" placeholder="end date"/>
    <input type="submit" valu="submit" placeholder="start date"/>
  </form>
  `;
  $("#credrep").click(function () {
    $(".rform").html($(form));
    $(".tr").html($());
  });
});

//all reports
$(document).ready(function () {
  let form = `
  <form method="post" action="./render_reports.php">
    <input type="texts" name="breports" id="breports" placeholder="start date"/>
    <input type="text" name="breporte" id="breporte" placeholder="end date"/>
    <input type="submit" value="submit" placeholder="start date"/>
  </form>
  `;
  $("#bankrep").click(function () {
    $(".rform").html($(form));
    $(".tr").html($());
  });
});

$(document).ready(function () {
  let form = `
  <form method="post" action="./render_reports.php">
    <input type="texts" name="chreps" id="chreps" placeholder="start date"/>
    <input type="text" name="chrepe" id="chrepe" placeholder="end date"/>
    <input type="submit" value="submit" placeholder="start date"/>
  </form>
  `;
  $("#cheqrep").click(function () {
    $(".rform").html($(form));
    $(".tr").html($());
  });
});

$(document).on('click','#stdt,#dreports,#creports,#breports,#chreps,chrepe', function(){
  $("#stdt").datepicker({ dateFormat: 'yy-mm-dd'}); //
  $("#eddt").datepicker({ dateFormat: 'yy-mm-dd' });

  $("#creports").datepicker({ dateFormat: 'yy-mm-dd', background: '#000' });
  $("#creporte").datepicker({ dateFormat: 'yy-mm-dd' });

  $("#dreports").datepicker({ dateFormat: 'yy-mm-dd', background: 'blue' });
  $("#dreporte").datepicker({ dateFormat: 'yy-mm-dd' });

  $("#breports").datepicker({ dateFormat: 'yy-mm-dd', background: 'blue' });
  $("#breporte").datepicker({ dateFormat: 'yy-mm-dd' });

  $("#chrepe").datepicker({ dateFormat: 'yy-mm-dd', background: 'blue' });
  $("#chreps").datepicker({ dateFormat: 'yy-mm-dd' });
});
