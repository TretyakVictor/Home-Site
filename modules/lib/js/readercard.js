function getKeyFromValue(ob, str) {
  for (var key in ob) {
    if (ob[key] === str) {
      return key;
    }
  }
}
var main = function() {
  "use strict";
  var dictionary;
  var dicdata = new Array();

  $.post("scr_readercard_books.php",
    function(data) {
      dictionary = jQuery.parseJSON(data);
      $.each(dictionary, function(index, el) {
        dicdata.push(el);
      });
  });
  $("input[name='booktarget']").on("keyup", function(event) {
    $("input[name='booktarget']").autocomplete({
      source: dicdata,
      minLength: 1
    });
  });
  $('#mainContent').submit(function(event) {
    if ($("input[name='booksreturn']").val()!="" && $("input[name='booktarget']").val()=="") {
      return true;
    } else if ($("input[name='booktarget']").val()!="" && (typeof(dictionary)!="undefined") && (typeof(dicdata)!="undefined")) {
      var key = getKeyFromValue(dictionary, $("input[name='booktarget']").val());
      $("input[name='booktarget']").val(key);
      return true;
    }else {
      console.log("undefined error");
      return false;
    }
  });
};
$(document).ready(main);
