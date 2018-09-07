var main = function() {
  "use strict";
  $('#content').hide();
  $('#mainContent').submit(function(event) {
    if ($("input[name='datein']").val() != "" && $("input[name='dateout']").val() != "") {
      $('#content').fadeOut(600, function() {
        $.post("scr_books_sph.php", {
            datein: $("input[name='datein']").val(),
            dateout: $("input[name='dateout']").val()
          },
          function(data) {
            $('#content').empty().html(data).fadeIn(500);
          });
      });
    } else {
      console.log("undefined error");
      $('#content').empty().html("Неизвестная ошибка.").fadeIn(500);
    }
    return false;
  });
};
$(document).ready(main);
