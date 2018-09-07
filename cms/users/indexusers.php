<?php
require_once '../utils/head.php';
echo "<p><a href=# onClick='history.back()'>Назад</a></p>";
if ($UserPrivilege == 1) {

  try {
    $page_link = 3;
    $page_number = 15;

    $obj = new PagerMySQL($pdo, $tbl_users, "", "ORDER BY id_users DESC", $page_number, $page_link);
    $name = "пользователя";
    if (!empty($_GET['page'])) {
      $page = intval($_GET['page']);
      echo "<a href=usersadd.php?page=$page titlе='Добавить {$name}'>Добавить {$name}</a><br><br>";
    } else {
      echo "<a href=usersadd.php?page=1 titlе='Добавить {$name}'>Добавить {$name}</a><br><br>";
    }
    $users = $obj->get_page();
    if (!empty($users)) {
      ?>
      <div class="table-responsive">
      <table class="table table-hover table-striped text-center">
        <caption>
          Пользователи
        </caption>
        <tr>
          <th>
            Статус
          </th>
          <th>
            Логин
          </th>
          <th>
            Почта
          </th>
          <th>
            Дата регистрации
          </th>
          <th>
            Действия
          </th>
        </tr>
      <?php
      for ($i=0; $i < count($users); $i++) {
        $class = "";
        if (!empty($_GET['page'])) {
          $page = $_GET['page'];
        }else {
          $page = 1;
        }
        $url = "?id_users={$users[$i]['id_users']}&page=$page";
        $users_url="";
        if (!empty($users[$i]['url'])) {
          $users[$i]['url'] = "http://{$users[$i]['url']}";
        }
        if (!empty($users[$i]['urltext'])) {
          $users_url = "<br><b>Ссылка:</b><a href='{$users[$i]['url']}'>{$users[$i]['urltext']}</a>";
        } elseif (!empty($users[$i]['url'])) {
          $users_url = "<br><b>Ссылка:</b><a href='{$users[$i]['url']}'>{$users[$i]['url']}</a>";
        }
        if ($users[$i]['block'] == 'block') {
          $class = "class='bad'";
          $block = "<a href=scr_usersunblock.php$url  title='Разблокировать пользователя'> Разблокировать</a>";
        } elseif ($users[$i]['id_users'] != $UserID) {
          $class = "class='good'";
          $block = "<a href=scr_usersblock.php$url title='Заблокировать пользователя'> Блокировать</a>";
        } else {
          $class = "class='normal'";
          $block = "";
        }
        if ($users[$i]['activation'] == 'deactivate') {
          $class = "class='bad'";
          $activate = "<a href=scr_usersactivation.php$url  title='Активировать пользователя'> Активировать</a><br>";
        }
        switch ($users[$i]['privilege']) {
          case '1':
            $privilege = 'Администратор';
            break;
          case '2':
            $privilege = 'Редактор';
            break;
          case '3':
            $privilege = 'Пользователь';
            break;
          default:
            $privilege = 'Не определено';
            break;
        }
        echo "<tr $class>
          <td>
            $privilege
          </td>
          <td>
          <a href=# onclick=\"show_detail('../../modules/system/user_page.php?usrlogin={$users[$i]['login']}', 600, 450); return false\">".htmlspecialchars($users[$i]['login'])."</a>
          </td>
          <td>
            {$users[$i]['email']}
          </td>
          <td>
            ".date("d.m.Y", strtotime($users[$i]['dateofregister']))."
          </td>";
          if ($users[$i]['id_users'] != $UserID) {
            echo "<td class='text-right'>
              $activate
              $block<br>
              <a href=# onClick=\"delete_position('scr_usersdel.php$url',"."'Вы действительно хотите удалить {$name}?');\"
              titlе='Удалить {$name}'>Удалить</а><br>
              <a href=scr_usersedit.php$url titlе='Редактировать {$name}'>Редактировать</а>
            </td>";
          } else {
            echo "<td>
              <a href=scr_usersedit.php$url titlе='Редактировать {$name}'>Редактировать</а>
            </td>";
          }
        echo "</tr>";
      }
      echo "</table></div><br>";
    }
    echo "<div class='text-center'>".$obj."</div>";
  } catch (ExceptionMySQL $e) {
    require '../utils/exception_mysql.php';
  }
}
require_once '../utils/bottom.php';
?>
<script src="../utils/js/new_window.js"></script>
