<li id="headBtn" type="button" data-toggle="dropdown">
  <a href="#"><span class="glyphicon glyphicon-log-in" aria-hidden="true"></span></a>
</li>
<ul class="dropdown-menu" id="secondMenuForm" aria-labelledby="headBtn">
  <form class="input-sm form-horizontal" role="form" method="POST">
    <div id="secondMenu">
      <div class="form-group secondMenuGrp">
        <label for="login" class="control-label col-sm-3">Логин</label>
        <div class="col-sm-9">
          <input type="text" name="login" class="form-control" placeholder="Логин" required/>
        </div>
      </div>
      <div class="form-group secondMenuGrp">
        <label for="password" class="control-label col-sm-3" >Пароль</label>
        <div class="col-sm-9">
          <input type="password" name="password" class="form-control" placeholder="Пароль" required/>
        </div>
      </div>
      <div class="form-group secondMenuGrp">
        <div class="col-sm-offset-3 col-sm-9">
          <button type="submit" name="submit_auth" class="btn btn-default btn-sm">Войти</button>
        </div>
      </div>
      <div class="form-group secondMenuGrp">
        <div class="col-sm-offset-3 col-sm-9 link" align="right">
          <li role="separator" class="divider"></li>
          <a href="register.php">Регистрация</a>
        </div>
      </div>
    </div>
  </form>
</ul>
