<?php require_once '../utils/scr_authorization.php'; ?>
<!DOCTYPE html>
<html lang="ru">
  <head>
    <meta http-equiv="content-type" content="text/html" charset="utf-8" />
    <title>Панель управления</title>
    <meta name="author" content="Tretyak Victor" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php require_once "scr&css.php"; ?>
  </head>
  <body>
    <div id="pageWrapper">
      <div id="wrapper">
        <!-- header -->
        <div class="container">
          <div class="link" id="header">
            <div class="col-md-3 headerPadd">
              <img class="headerLogo" src="../../imgStyle/headLogo.jpg">
            </div>
            <div class="col-md-9 headerPadd">
              <div id="headerAuth">
                <?php require_once 'user.php';  ?>
              </div>
            </div>
          </div>
        </div>

        <!-- Меню -->
        <div class="container">
          <nav class="navbar navbar-default" id="navTheme">
            <div class="navbar-header">
              <button type="button" class="navbar-toggle collapsed" id="navBarBtn" data-toggle="collapse" data-target="#navbar-collapse-1" aria-expanded="false">
                <!-- <span class="sr-only">Toggle navigation</span> -->
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
              </button>
              <a class="navbar-brand" href="../index.php"><span class="glyphicon glyphicon-home" aria-hidden="true"></span></a>
            </div>
            <div class="collapse navbar-collapse" id="navbar-collapse-1">
              <!-- Контент -->
              <ul class="nav navbar-nav">
                <li><a href="../../index.php">На сайт</a></li>
              </ul>
              <!-- Контент -->
            </div>
          </nav>
        </div>

        <!-- main -->
        <div class="container">
          <div id="pageBack">
            <div class="row">
              <?php if ($UserPrivilege == 1 || $UserPrivilege == 2) { ?>
              <!-- Левое меню -->
              <div class="col-xs-12 col-md-3 left-sidebar">
                <div class="navbar">
                  <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed btnPos" data-toggle="collapse" data-target="#navbar-second-collapse-1">
                      <span class="glyphicon glyphicon-th-list"></span>
                    </button>
                  </div>
                  <div class="collapse navbar-collapse navSecondBarCollapse" id="navbar-second-collapse-1">
                    <ul class="nav nav-pills nav-stacked">
                      <?php require_once "menu.php"; ?>
                    </ul>
                  </div>
                </div>
              </div>
              <script src="../utils/js/menu.js"></script>
              <?php } ?>
              <!-- Контент -->
              <div class="col-xs-12 col-md-9 content link">
