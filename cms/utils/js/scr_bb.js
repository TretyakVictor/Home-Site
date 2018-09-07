function tag(text1, text2) {
  if ((document.selection)) {
    document.form.name.focus();
    document.form.document.selection.createRange().text = text1 + document.form.document.selection.createRange().text + text2;
  } else if (document.forms['form'].elements['name'].selectionStart != undefined) {
    var element = document.forms['form'].elements['name'];

    var scroll = element.scrollTop;
    var str = element.value;
    var start = element.selectionStart;
    var length = element.selectionEnd - element.selectionStart;
    element.value = str.substr(0, start) + text1 + str.substr(start, length) + text2 + str.substr(start + length);

    element.scrollTop = scroll;
  } else document.form.name.value += text1 + text2;
}
