var main = function() {
  "use strict";
  $('#content').hide();
  $.post("scr_goods.php",
    {
      user: $("input[name='id_user']").val(),
      sort: $("select[name='sort']").val(),
      order: $("select[name='order']").val()
    },
    function(data) {
      $('#content').empty().html(data).fadeIn(300);
  });
  $("form[name='form_search']").submit(function(event) {
    if ($("input[name='search_val']").val() != "") {
      $('#content').fadeOut(600, function () {
        $.post("scr_goods.php",
        {
          user: $("input[name='id_user']").val(),
          sort: $("select[name='sort']").val(),
          order: $("select[name='order']").val(),
          search_val: $("input[name='search_val']").val(),
          search: $("select[name='search']").val()
        },
        function(data) {
          $('#content').empty().html(data).fadeIn(500);
        });
      });
    }
    return false;
  });
  $("form[name='form_sort']").submit(function(event) {
    $('#content').fadeOut(600, function () {
      $.post("scr_goods.php",
      {
        user: $("input[name='id_user']").val(),
        sort: $("select[name='sort']").val(),
        order: $("select[name='order']").val()
      },
      function(data) {
        $('#content').empty().html(data).fadeIn(500);
      });
    });
    return false;
  });
};
$(document).ready(main);
