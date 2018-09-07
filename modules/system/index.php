<?php
require_once '../templates/head.php';

require_once '../../configs/classes.config.php';
require_once '../../configs/mysql.config.php';
require_once '../../cms/utils/print_page.php';

try {
  if (empty($_GET['id_news'])) {
    if (!empty($_GET['page'])) {
      $_GET['page'] = intval($_GET['page']);
    }
    $page_link = 3;
    $page_number = 5;
    $obj = new PagerMySQL($pdo, $tbl_news, "WHERE hide = 'show'", "ORDER BY putdate DESC", $page_number, $page_link);

    $news = $obj->get_page();

    if (!empty($news)) {
      $patt = array("[b]", "[/b]", "[i]", "[/i]");
      $repl = array("", "", "", "");
      $pattern_url = "|\[url[^\]]*\]|";
      $pattern_b_url = "|\[/url[^\]]*\]|";
      for ($i=0; $i < count($news); $i++) {
        if (strlen($news[$i]['body']) > 600) {
          $news[$i]['body'] = substr($news[$i]['body'], 0, 1600)."...";
          $news[$i]['body'] = str_replace($patt, $repl, $news[$i]['body']);
          $news[$i]['body'] = preg_replace($pattern_url, "", $news[$i]['body']);
          $news[$i]['body'] = preg_replace($pattern_b_url, "", $news[$i]['body']);
        }
        $news_url = "";
        if (!empty($news[$i]['url'])) {
          if (!preg_match("|^http://|i", $news[$i]['url'])) {
            $news[$i]['url'] = "http://{$news[$i]['url']}";
          }
          if (empty($news[$i]['urltext'])) {
            $news_url = "<br><b>Ссылка: </b><a href='{$news[$i]['url']}'>{$news[$i]['url']}</a>";
          }else {
            $news_url = "<br><b>Ссылка: </b><a href='{$news[$i]['url']}'>{$news[$i]['urltext']}</a>";
          }
        }
        echo "<div class='well well-sm'>
          <table>
            <tr>
              <td class='news-title'>
                <h2>".print_page($news[$i]['name'])."</h2>
              </td>
              <td class='text-right news-date'>
                ".$news[$i]['putdate']."
              </td>
            </tr>
            <tr>
              <td colspan='2'>
              ".print_page($news[$i]['body'])."
              </td>
            </tr>
            <tr>";
              if (!empty($news_url)) {
                echo "
                <td class='news-url'>
                  $news_url
                </td>
                <td class='text-right'>
                  <a href=\"index.php?id_news=".$news[$i]['id_news']."\" >
                    <i class='glyphicon glyphicon-eye-open'></i>&nbspчитать
                  </a>
                </td>";
              }else {
                echo "
                <td colspan='2' class='text-right'>
                  <a href=\"index.php?id_news=".$news[$i]['id_news']."\" >
                    <i class='glyphicon glyphicon-eye-open'></i>&nbspчитать
                  </a>
                </td>";
              }
            echo "</tr>
          </table>
        </div>";
      }
      echo "<div class='text-center'>".$obj->print_page()."</div>";
    }
  }else {
    $_GET['id_news'] = intval($_GET['id_news']);

    $res = $pdo->prepare("SELECT id_news, name, body, putdate, url, urltext, urlpict, hide
    FROM $tbl_news WHERE hide = 'show' AND id_news = ?");
    $res->execute([$_GET['id_news']]);
    $dataerr = $res->fetchAll();
    $news = $dataerr[0];

    $url_pict = "";
    if ($news['urlpict'] != '' && $news['urlpict'] != '-') {
      $url_pict = "<img src=".print_page($news['urlpict']).">";
    }
    $news_url = "";
    if (!empty($news['url'])) {
      if (!preg_match("|^http://|i", $news['url'])) {
        $news['url'] = "http://{$news['url']}";
      }
      if (empty($news['urltext'])) {
        $news_url = "<br><b>Ссылка: </b><a href='{$news['url']}'>{$news['url']}</a>";
      }else {
        $news_url = "<br><b>Ссылка: </b><a href='{$news['url']}'>{$news['urltext']}</a>";
      }
    }
    echo "<div class='well well-sm'><b>".$news['putdate']."</b> <h1>".print_page($news['name']).
    "</h1><br> $url_pict ".nl2br(print_page($news['body']))."<br>$news_url</div>";
  }

} catch (ExceptionMySQL $e) {
  echo "error!";
} catch (ExceptionObject $e) {
  echo "error!";
} catch (ExceptionMember $e) {
  echo "error!";
}

require_once '../templates/bottom.php';
?>
