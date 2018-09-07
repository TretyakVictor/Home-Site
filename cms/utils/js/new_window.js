function show_detail(url, width, height) {
  var a;
  var b;
  var url;
  vidWindowWidth = width;
  vidWindowHeight = height;
  a = (screen.height - vidWindowHeight) / 2;
  b = (screen.width - vidWindowWidth) / 3;
  console.log(a, b);
  features = "top=" + a + ", left=" + b +
    ",width=" + vidWindowWidth +
    ",height=" + vidWindowHeight +
    ",toolbar=no,menubar=no,location=no," +
    "directories=no,scrollbars=no,resizable=no";
  window.open(url, '', features, true);
}
