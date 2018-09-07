<?php require_once '../templates/head.php';
require_once '../../configs/classes.config.php';
require_once '../../configs/mysql.config.php';

if ($_GET['usrlogin']) {
  $userlogin = $_GET['usrlogin'];
  if ($UserName == $userlogin) {
    $stmt = $pdo->prepare("SELECT `id_users`, `email`, `yearborn`, `about`, `lastvisit`, `dateofregister` FROM $tbl_users WHERE `login` = ? LIMIT 1");
    $stmt->execute([$userlogin]);
    $stmt = $stmt->fetchAll();
    $userdata = $stmt[0];

    echo "<h1>Страница пользователя</h1>"."<br>";
    echo "<table class='table table-hover table-striped text-left'>
      <caption>
        Пользователь $userlogin
      </caption>
      <tr>
        <td>
          Электронная почта
        </td>
        <td>
          $userdata[email]
        </td>
      </tr>
      <tr>
        <td>
          Последний визит
        </td>
        <td>
          $userdata[lastvisit]
        </td>
      </tr>
      <tr>
        <td>
          Дата регистрации
        </td>
        <td>
          $userdata[dateofregister]
        </td>
      </tr>
      <tr>
        <td>
          Дата рождения
        </td>
        <td>
          $userdata[yearborn]
        </td>
      </tr>
      <tr>
        <td>
          О себе
        </td>
        <td>
          $userdata[about]
        </td>
      </tr>
    </table>";

    if (!empty($_GET['del_note'])) {
      $stmt = $pdo->prepare("DELETE FROM $tbl_notes WHERE id_notes = ? LIMIT 1");
      $stmt->execute([intval($_GET['del_note'])]);
      unset($_GET['del_note']);
    }

    $title = new FieldText("title", "Заголовок", $_POST['title'], false);
    $note = new FieldTextarea("note", "Заметка", $_POST['note'], true);
    $usrlogin = new FieldHidden("usrlogin",  $_REQUEST['usrlogin'], true);
    $form = new Form(array("title" => $title, "note" => $note, "usrlogin" => $usrlogin), "Добавить");

    if (!empty($_POST)) {
      $error = $form->check();
      if (empty($error)) {
        $sql = "INSERT INTO $tbl_notes SET title = ?, note = ?, putdate = ?, id_user = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(1, $form->fields['title']->get_value(), PDO::PARAM_STR);
        $stmt->bindValue(2, $form->fields['note']->get_value(), PDO::PARAM_STR);
        $stmt->bindValue(3, date("Y-m-d G:i:s"), PDO::PARAM_STR);
        $stmt->bindValue(4, $userdata['id_users'], PDO::PARAM_INT);
        if ($stmt->execute()) {
          exit("<meta http-equiv='refresh' content='0; url=$_SERVER[PHP_SELF]?usrlogin={$form->fields[usrlogin]->get_value()}'>");
        }
      }
    }
    if (!empty($error)) {
      foreach ($error as $err) {
        echo "<span style=\"color:red\">$err</span><br>";
      }
    }

    echo "<div class='ui-widget'>
      [<a data-toggle='collapse' data-target='#hiddenf1'>Новая заметка</a>]
      <div id='hiddenf1' class='collapse'>";
    $form->print_form();
    echo "</div></div><br>";

    $stmt = $pdo->prepare("SELECT `id_notes`, `title`, `note`, `putdate` FROM $tbl_notes WHERE `id_user` = ? ORDER BY `putdate` DESC");
    $stmt->execute([$userdata['id_users']]);
    $stmt = $stmt->fetchAll();
    echo "<div id='notes-wall'>
    <h3>
      Заметки
    </h3>";
    $counter = count($stmt);
    if ($counter > 0) {
      for ($i=0; $i < $counter; $i++) {
        echo "<div class='well well-sm'>
          <table class='table table-condensed table-without-bottom'>
            <caption>
              {$stmt[$i]['putdate']}
            </caption>";
            if (!empty($stmt[$i]['title'])) {
              echo "
              <tr class='text-left'>
                <td>
                  {$stmt[$i]['title']}
                </td>
              </tr>";
            }
            echo "<tr class='text-left'>
              <td>
                {$stmt[$i]['note']}
              </td>
            </tr>
            <tr class='text-right'>
              <td>
                <span><a href='?usrlogin={$form->fields['usrlogin']->get_value()}&del_note={$stmt[$i]['id_notes']}'>Удалить</a></span>
              </td>
            </tr>
          </table>
        </div>";
      }
    } else {
      echo "Пусто...";
    }
    echo "</div>";
  } else {
    $stmt = $pdo->prepare("SELECT `about`, `lastvisit`, `dateofregister` FROM $tbl_users WHERE `login` = ? LIMIT 1");
    $stmt->execute([$userlogin]);
    $stmt = $stmt->fetchAll();
    $userdata = $stmt[0];

    echo "<h1>Страница пользователя</h1>"."<br>";
    echo "<table class='table table-hover table-striped text-left'>
      <caption>
        Пользователь $userlogin
      </caption>
      <tr>
        <td>
          Последний визит
        </td>
        <td>
          $userdata[lastvisit]
        </td>
      </tr>
      <tr>
        <td>
          Дата регистрации
        </td>
        <td>
          $userdata[dateofregister]
        </td>
      </tr>
      <tr>
        <td>
          О себе
        </td>
        <td>
          $userdata[about]
        </td>
      </tr>
    </table>";
  }

}
?>
<?php require_once '../templates/bottom.php'; ?>
