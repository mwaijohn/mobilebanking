
$(document).ready(function () {
    $("#add").click(function () {
        $(`<br><br><input type='number' disabled/> <input type='number' id=''iddi' name='shdhdhg'/> <input type='number' placeholder='charges'/>`).insertBefore('#add');
        //$(".rates-form").append($("<br><br><br><input type='number' disabled/> <input type='number' /> <input type='number' />"));

        $('#iddi').on('click','#iddi',function (e) { 
            e.preventDefault();
            alert("blur");
        });
    });

    $('.rates-form').on('keyup', 'input[type="number"]', function () {
        $("#srr").val($("#rr").val()+1);
    });
  });

  