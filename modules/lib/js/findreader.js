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

  $.post("scr_findreader.php",
    function(data) {
      dictionary = jQuery.parseJSON(data);
      $.each(dictionary, function(index, el) {
        dicdata.push(el);
      });
  });

  $("input[name='readertarget']").on("keyup", function(event) {
    $("input[name='readertarget']").autocomplete({
      source: dicdata,
      minLength: 1
    });
  });
  $('#mainContent').submit(function(event) {
    if ($("input[name='readertarget']").val()!="" && (typeof(dictionary)!="undefined") && (typeof(dicdata)!="undefined")) {
      var key = getKeyFromValue(dictionary, $("input[name='readertarget']").val());
      $("input[name='readertarget']").val(key);
      $("input[type='submit']").prop("disabled", true);
      return true;
    }else {
      console.log("undefined error");
      return false;
    }
  });
};
$(document).ready(main);
