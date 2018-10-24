$(document).ready(function () {
  $("#bal").click(function () {
    $.post("../banking/services/render_services.php",
      {
        bal: "bal",
      },
      function (data, status) {
        //alert(data);
        let dataObj = JSON.parse(data);

        let appendData = `
        <small>Name: &nbsp&nbsp&nbsp <strong>${dataObj[0]}</strong></small><br>
        <small>Account no: &nbsp&nbsp&nbsp <strong>${dataObj[1]}</strong></small><br>
        <small>Balance: &nbsp&nbsp&nbsp <strong>${dataObj[2]} Ksh</strong></small><br>
        `;
        document.querySelector('.working').innerHTML = "";
        $(".working").append($(appendData));
        //alert(dataObj[0]);
      });
  });
});

$(document).ready(function () {
  let form = `
  <input type="number" style = 'width:50%;margin-top:15px;' id="depno" name="depno" placeholder="Enter account to deposit to" required>
  <input type="number" style = 'width:50%;margin-top:15px;' id="dep" name="deposit" placeholder="Enter amount" required>
  <input type="button" style = 'width:50%'value="Deposit" id="sure-deposit">
  `;
  $("#deposit").click(function () {
    $('.working').css({"display":"block"});
    //$(".working").append($(""));
    //
    //document.querySelector('.working').innerHTML = "";
    $(".working").html($(form));
  });
});

$(document).on('click','#sure-deposit', function(){
    $.post("../banking/services/render_services.php",
      {
        deposit:$('#dep').val() ,
        amount:$('#depno').val()
      },
      function (data, status) {
        //alert(data);
        let dataObj = JSON.parse(data);
        // alert(dataObj[0]);
        if(dataObj[0]=='success'){
          let success = `
        <div>Balance deposited successively</div>
        `;
        $(".working").html($(success));
        }else{
          ///alert("failed");
          let fail = `
        <div>Failed to make deposit</div>
        `;
        $(".working").html($(fail));
        }
      });
});

//pay issuedCheque
$(document).ready(function () {
  $("#ppcheq").click(function () {
    $(".working").html($());
    $('.working').css({"display":"block"});
    $.post("../banking/services/render_services.php",
      {
        ppcheq: "someval",
      },
      function (data, status) {
        //alert(data);
        let ref = JSON.parse(data);
        let option = "<select class='select' id='utosearch'>"
        option += "<option>unpaid cheques</option>"
        for(let i=0;i<ref.length;i++){
          option += `<option value=${ref[i]}>${ref[i]}</option>`;
        }
        option += "</select><br>" + '<input type="button" style = "width:50%"value="Pay cheque" id="sure-pcheq">';
        //form + = option;
        $(".working").append($(option));
        //alert(dataObj[0]);
      });
    //$(".working").html($(form));
  });
});
//sure pay cheque
$(document).on('click','#sure-pcheq', function(){
  $.post("../banking/services/render_services.php",
    {
      refnoo:$( ".select option:selected" ).text() ,
    },
    function (data, status) {
      //alert(data);
      let dataObj = JSON.parse(data);
      if(dataObj[0]=='success'){
        let success = `
        <div>payment successfull</div>
        `;
        $(".working").html($(success));
      }else{
        alert("failed to make payment");
      }
    });
});
$(document).ready(function () {

  //  <input type="text" style = 'width:50%;margin-top:15px;' id="acheq" name="cheque" placeholder="Enter account number" required>
  let form = `
  <input type="text" style = 'width:50%;margin-top:15px;' id="cheq" name="cheque" placeholder="Enter ref number" required>
  <input type="button" style = 'width:50%'value="Cancel" id="sure-canccheq">
  `;
  $("#ccheq").click(function () {
    $('.working').css({"display":"block"});
    $(".working").html($(form));
  });
});


$(document).on('click','#sure-canccheq', function(){
  $.post("../banking/services/render_services.php",
    {
      ref:$('#cheq').val() ,
    },
    function (data, status) {
      //alert(data);
      //console.log("dat" + data);
      let dataObj = JSON.parse(data);
      if(dataObj[0]=='success'){
        let success = `
        <div>Your cheque was successively cancelled</div>
        `;
        $(".working").html($(success));
      }else{
        alert("no such cheque");
      }
    });
});

//usieing cheque
$(document).on('click','#sure-issuecheq', function(){
  $.post("../banking/services/render_services.php",
    {
      pcamount:$('#pcamount').val() ,
      pcheq:$('#pcheq').val()
    },
    function (data, status) {
      alert(data);
      let dataObj = JSON.parse(data);
      if(dataObj[0]=='success'){
        let success = `
        <div>Payment successfull <form action="../banking/services/render_services.php"
         method="post">
        <input type="hidden" name="printcheq" value="cheque"><input style='width:50%;'
        type="submit" value="print cheque"></form></div>
        `;
        $(".working").html($(success));
        $.post("../banking/services/render_services.php",
              {
                  printcheq: "cheque",
              },
              function(data, status){
                  //alert(data);
              });
      }else{
        let fail = `
        <div>Failed to pay amount</div>
        `;
        $(".working").html($(fail));
      }
    });
});

$(document).ready(function () {

  //  <input type="text" style = 'width:50%;margin-top:15px;' id="acheq" name="cheque" placeholder="Enter account number" required>
  let form = `
  <input type="text" style = 'width:50%;margin-top:15px;' id="pcheq" name="pcheq" placeholder="Account number to pay" required>
  <input type="text" style = 'width:50%;margin-top:15px;' id="pcamount" name="pcamount" placeholder="Amount" required>
  <input type="button" style = 'width:50%'value="Pay" id="sure-issuecheq">
  `;
  $("#issuecheq").click(function () {
    //$('.working').css({"display":"block"});
    $(".working").html($(form));
  });
});

$(document).ready(function () {
  let form = `
  <input type="password" style = 'width:75%;margin-top:15px;' id="oldpass" name="oldpass" placeholder="Old password" required>
  <input type="password" style = 'width:50%;margin-top:15px;' id="newpass" name="newpass" placeholder="Enter new Pin" required>
  <input type="password" style = 'width:50%;margin-top:15px;' id="newpass2" name="newpass2" placeholder="Confirm pin" required>
  <input type="button" style = 'width:50%'value="Change pin" id="pn">
  `;
  $("#change-pin").click(function () {
    $(".working").html($(form));
  });
});

$(document).on('click','#pn', function(){
  //console.log($('#newpass').val());
  if($('#newpass').val()=="" || $('#oldpass').val() == "" || $('#newpass2').val()==""){
    alert("Enter all fields");
  }else{
    if($('#newpass2').val()  != $('#newpass').val()){
      alert("Password do not match")
    }else{
      $.post("../banking/services/render_services.php",
        {
          newpass:$('#newpass').val(),
          oldpass:$('#oldpass').val()
        },
        function (data, status) {
        //  alert(data);
          let dataObj = JSON.parse(data);
          if(dataObj[0]=='success'){
            let success = `
            <div>Pin changed successfully</div>
            `;
            $(".working").html($(success));
          }else{
            // alert("Wrong old password");
              //alert(dataObj[0]);
              let fail = `
              <div>Wrong old password</div>
              `;
              $(".working").html($(fail));
          }
        });
    }
  }
});

//revoke cashier and asign Cashier
$(document).ready(function () {
  $("#assigncashier").click(function () {
    //$(".working").html($(form));
    $.post("../banking/services/render_services.php",
    {
        accounts: "accounts"
    },
    function(data, status){
        //alert("Data: " + data + "\nStatus: " + status);
        let ref = JSON.parse(data);
        let option = "<form action='../banking/services/render_services.php' method='post'><select class='select' name='account'>";
        option += "<option>accounts</option>";
        ref.forEach(function (item) {
        option += `<option name= "account" value=${item['accno']}>${item['firstname']} ${item['lastname']}  ${item['accno']} </option>`;
        });
        option += "</select><br>" +
        "<input type='hidden' name='makecashier' value='make'>"+
        '<input type="submit" style = "width:50%"value="Make cashier" id="sure-assigncashier"></form>';
        //form + = option;
        $(".working").html($(option));
    });
  });
});

//revoke cashier
$(document).ready(function () {
  $("#revokecashier").click(function () {
    //$(".working").html($(form));
    $.post("../banking/services/render_services.php",
    {
        revaccounts: "accounts"
    },
    function(data, status){
        //alert("Data: " + data + "\nStatus: " + status);
        let ref = JSON.parse(data);
        let option = "<form action='../banking/services/render_services.php' method='post'><select class='select' name='account'>";
        option += "<option>accounts</option>";
        ref.forEach(function (item) {
        option += `<option name= "account" value=${item['accno']}>${item['firstname']} ${item['lastname']}  ${item['accno']} </option>`;
        });
        option += "</select><br>" +
        "<input type='hidden' name='revokecashier' value='revoke'>"+
        '<input type="submit" style = "width:50%"value="Revoke Cashier" id="sure-revokecashier"></form>';
        //form + = option;
        $(".working").html($(option));
    });
  });
});
let count = $('.service-btn button').length;
if(count==1){
  $('.service-btn').css({"grid-template-columns":"1fr"});
}
if(count==2){
  $('.service-btn').css({"grid-template-columns":"1fr 1fr"});
}
if(count==3){
  $('.service-btn').css({"grid-template-columns":"1fr 1fr 1fr"});
}
if(count==4){
  $('.service-btn').css({"grid-template-columns":"1fr 1fr 1fr 1fr"});
}
if(count==5){
  $('.service-btn').css({"grid-template-columns":"1fr 1fr 1fr 1fr 1fr"});
}
if(count==6){
  $('.service-btn').css({"grid-template-columns":"1fr 1fr 1fr 1fr 1fr 1fr"});
}
if(count==7){
  $('.service-btn').css({"grid-template-columns":"1fr 1fr 1fr 1fr 1fr 1fr 1fr"});
}

// $(".button-collapse").sideNav();
$( function() {
  $.widget( "custom.combobox", {
    _create: function() {
      this.wrapper = $( "<span>" )
        .addClass( "custom-combobox" )
        .insertAfter( this.element );

      this.element.hide();
      this._createAutocomplete();
      this._createShowAllButton();
    },

    _createAutocomplete: function() {
      var selected = this.element.children( ":selected" ),
        value = selected.val() ? selected.text() : "";

      this.input = $( "<input>" )
        .appendTo( this.wrapper )
        .val( value )
        .attr( "title", "" )
        .addClass( "custom-combobox-input ui-widget ui-widget-content ui-state-default ui-corner-left" )
        .autocomplete({
          delay: 0,
          minLength: 0,
          source: $.proxy( this, "_source" )
        })
        .tooltip({
          classes: {
            "ui-tooltip": "ui-state-highlight"
          }
        });

      this._on( this.input, {
        autocompleteselect: function( event, ui ) {
          ui.item.option.selected = true;
          this._trigger( "select", event, {
            item: ui.item.option
          });
        },

        autocompletechange: "_removeIfInvalid"
      });
    },

    _createShowAllButton: function() {
      var input = this.input,
        wasOpen = false;

      $( "<a>" )
        .attr( "tabIndex", -1 )
        .attr( "title", "Show All Items" )
        .tooltip()
        .appendTo( this.wrapper )
        .button({
          icons: {
            primary: "ui-icon-triangle-1-s"
          },
          text: false
        })
        .removeClass( "ui-corner-all" )
        .addClass( "custom-combobox-toggle ui-corner-right" )
        .on( "mousedown", function() {
          wasOpen = input.autocomplete( "widget" ).is( ":visible" );
        })
        .on( "click", function() {
          input.trigger( "focus" );

          // Close if already visible
          if ( wasOpen ) {
            return;
          }

          // Pass empty string as value to search for, displaying all results
          input.autocomplete( "search", "" );
        });
    },

    _source: function( request, response ) {
      var matcher = new RegExp( $.ui.autocomplete.escapeRegex(request.term), "i" );
      response( this.element.children( "option" ).map(function() {
        var text = $( this ).text();
        if ( this.value && ( !request.term || matcher.test(text) ) )
          return {
            label: text,
            value: text,
            option: this
          };
      }) );
    },

    _removeIfInvalid: function( event, ui ) {

      // Selected an item, nothing to do
      if ( ui.item ) {
        return;
      }

      // Search for a match (case-insensitive)
      var value = this.input.val(),
        valueLowerCase = value.toLowerCase(),
        valid = false;
      this.element.children( "option" ).each(function() {
        if ( $( this ).text().toLowerCase() === valueLowerCase ) {
          this.selected = valid = true;
          return false;
        }
      });

      // Found a match, nothing to do
      if ( valid ) {
        return;
      }

      // Remove invalid value
      this.input
        .val( "" )
        .attr( "title", value + " didn't match any item" )
        .tooltip( "open" );
      this.element.val( "" );
      this._delay(function() {
        this.input.tooltip( "close" ).attr( "title", "" );
      }, 2500 );
      this.input.autocomplete( "instance" ).term = "";
    },

    _destroy: function() {
      this.wrapper.remove();
      this.element.show();
    }
  });

  $( "#utosearch" ).combobox();
} );
