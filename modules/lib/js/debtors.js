var main = function() {
  "use strict";
  $('#content').hide();
  $.post("scr_debtors.php",
    {
      selectrdr: $("select[name='selectrdr']").val(),
      id_catalog: $("input[name='id_catalog']").val()
    },
    function(data) {
      $('#content').empty().html(data).fadeIn(300);
  });
  $('#mainContent').submit(function(event) {
    if ($("input[type='submit']").val()!="") {
      $('#content').fadeOut(600, function () {
        $.post("scr_debtors.php",
        {
          selectrdr: $("select[name='selectrdr']").val(),
          id_catalog: $("input[name='id_catalog']").val()
        },
        function(data) {
          $('#content').empty().html(data).fadeIn(500);
        });
      });
    }else {
      console.log("undefined error");
    }
    return false;
  });
};
$(document).ready(main);
