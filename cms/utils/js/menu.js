function setCookie(name, value, path, expires, domain, secure) {
  if (!name || value === undefined) return false;
  var str = name + '=' + encodeURIComponent(value);
  if (expires) str += '; expires=' + expires.toGMTString();
  if (path) str += '; path=' + path;
  if (domain) str += '; domain=' + domain;
  if (secure) str += '; secure';
  document.cookie = str;
  return true;
}

function getCookie(name) {
  var pattern = "(?:; )?" + name + "=([^;]*);?";
  var regexp = new RegExp(pattern);

  if (regexp.test(document.cookie))
    return decodeURIComponent(RegExp["$1"]);

  return false;
}

function deleteCookie(name, path, domain) {
  setCookie(name, null, path, new Date(0), domain);
  return true;
}
var main = function() {
  "use strict";
  $("#navbar-second-collapse-1").click(function(event) {
    var id_click = event.target.id;
    if (getCookie(id_click)) {
      console.log("del");
      deleteCookie(id_click, "/cms");
    } else {
      setCookie(id_click, "true", "/cms");
    }
  });
};
$(document).ready(main);
