<?php
if (USER_LOGGED) {
  if (!check_user($UserID)) {
    logout();
  }?>
  <ul class="list-group">
    <li class="list-group-item">Пользователь: <?php echo "<a href='#'>{$UserName}</a>"; ?></li>
    <li class="list-group-item">Статус: <?php switch ($UserPrivilege) {
      case '1':
        echo "Администратор";
        break;
      case '2':
        echo "Редактор";
        break;

      default:
        echo "Пользователь";
        break;
    } ?> </li>
  </ul>

<?php } ?>
