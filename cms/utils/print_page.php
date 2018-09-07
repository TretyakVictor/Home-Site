<?php
function print_page($postbody){
  $postbody = preg_replace_callback("|([a-zа-я\d!]{35,})|i", "split_text", $postbody);
  $postbody = htmlspecialchars($postbody, ENT_QUOTES);
  $pattern = "#\[b\](.+)\[\/b\]#isU";
  $postbody = preg_replace($pattern, '<b>\\1</b>', $postbody);
  $pattern = "#\[i\](.+)\[\/i\]#isU";
  $postbody = preg_replace($pattern, '<i>\\1</i>', $postbody);
  $pattern = "#\[u\](.+)\[\/u\]#isU";
  $postbody = preg_replace($pattern, '<span style="text-decoration:underline">\\1</span>', $postbody);
  $pattern = "#\[sup\](.+)\[\/sup\]#isU";
  $postbody = preg_replace($pattern, '<sup>\\1</sup>', $postbody);
  $pattern = "#\[sub\](.+)\[\/sub\]#isU";
  $postbody = preg_replace($pattern, '<sub>\\1</sub>', $postbody);
  $pattern = "#\[url\][\s]*([\S]*)[\s]*\[\/url\]#si";
  $postbody = preg_replace_callback($pattern, "url_replace", $postbody);
  $pattern = "#\[url[\s]*=[\s]*([\S]+)[\s]*\][\s]*([^\[]*)\[/url\]#isU";
  $postbody = preg_replace_callback($pattern, "url_replace_name", $postbody);
  $pattern = "#\[quote\](.+)\[\/quote\]#isU";
  $postbody = preg_replace($pattern, '<table width = "95%"><tr><td>Цитата</td></tr><tr><td class="quote">\\1</td></tr></table>', $postbody);
  $pattern = "#\[size=(.+?)\](.+?)\[\/size\]#isU";
  $postbody = preg_replace($pattern, '<span style="font-size:\\1%">\\2</span>', $postbody);
  $pattern = "#\[color=(.+?)\](.+?)\[\/color\]#is";
  $postbody = preg_replace($pattern, '<span style="color:\\1">\\2</span>', $postbody);

  $pattern = "#\[th\](.+)\[\/th\]#isU";
  $postbody = preg_replace($pattern, '<th>\\1</th>', $postbody);
  $pattern = "#\[tr\](.+)\[\/tr\]#isU";
  $postbody = preg_replace($pattern, '<tr>\\1</tr>', $postbody);
  $pattern = "#\[td\](.+)\[\/td\]#isU";
  $postbody = preg_replace($pattern, '<td>\\1</td>', $postbody);

  $pattern = "#\[tr=(.+?)\](.+?)\[\/tr\]#is";
  $postbody = preg_replace($pattern, '<tr class="\\1">\\2</tr>', $postbody);
  $pattern = "#\[td=(.+?)\](.+?)\[\/td\]#is";
  $postbody = preg_replace($pattern, '<td class="\\1">\\2</td>', $postbody);

  $pattern = "#\[pre\](.+)\[\/pre\]#isU";
  $postbody = preg_replace($pattern, '<pre>\\1</pre>', $postbody);
  $pattern = "#\[code\](.+)\[\/code\]#isU";
  $postbody = preg_replace($pattern, '<code>\\1</code>', $postbody);
  $pattern = "#\[code=(.+?)\](.+?)\[\/code\]#is";
  $postbody = preg_replace($pattern, '<code data-language="\\1">\\2</code>', $postbody);
  return $postbody;
}
function url_replace($matches){
  if (substr($matches[1], 0, 7) != "http://" && substr($matches[1], 0, 8) != "https://") {
    $matches[1] = "http://".$matches[1];
    return "<a href='$matches[1]' class=news_txt_lnk>$matches[1]</a>";
  } else {
    return "<a href='$matches[1]' class=news_txt_lnk>$matches[1]</a>";
  }
}
function url_replace_name($matches){
  if (substr($matches[1], 0, 7) != "http://" && substr($matches[1], 0, 8) != "https://") {
    $matches[1] = "http://".$matches[1];
    return "<a href='$matches[1]' class=news_txt_lnk>$matches[2]</a>";
  } else {
    return "<a href='$matches[1]' class=news_txt_lnk>$matches[2]</a>";
  }
}
function split_text($matches) {
  return wordwrap($matches[1], 35, ' ', 1);
}
?>
