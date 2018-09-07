<?php
if (USER_LOGGED) {
  if (!check_user($UserID)) {
    logout();
  }?>
  <ul class="list-group">
    <li class="list-group-item">
      <?php echo "<a href='user_page.php?usrlogin={$UserName}'>{$UserName}</a>"; ?>
      <span class="glyphicon glyphicon-user user-icon"></span>
    </li>
    <li class="list-group-item"><a href="?logout">Выход</a></li>
  </ul>

<?php } ?>
