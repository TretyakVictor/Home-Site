<?php
  require_once '../templates/scr_authorization.php';
?>
<!DOCTYPE html>
<html lang="ru">
  <head>
    <meta http-equiv="content-type" content="text/html" charset="utf-8" />
    <title>Home Server</title>
    <meta name="author" content="Tretyak Victor" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php require_once 'js&css.php' ?>
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
                <?php require_once 'user_on.php';  ?>
              </div>
            </div>
          </div>
        </div>

        <!-- Меню -->
        <div class="container">
          <nav class="navbar navbar-default" id="navTheme">
            <div class="navbar-header">
              <button type="button" class="navbar-toggle collapsed" id="navBarBtn" data-toggle="collapse" data-target="#navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
              </button>
              <a class="navbar-brand" href="/index.php" title="На главную"><span class="glyphicon glyphicon-home"></span></a>
            </div>
            <div class="collapse navbar-collapse" id="navbar-collapse-1">
              <!-- Контент -->
              <ul class="nav navbar-nav">
                <?php require_once 'menu.php'; ?>
              </ul>
              <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                  <?php require_once('user_off.php');  ?>
                </li>
              </ul>
              <!-- Контент -->
            </div>
          </nav>
        </div>

        <!-- main -->
        <div class="container">
          <div id="pageBack">
            <div class="row">
              <!-- Правое меню -->
              <?php require_once 'submenu.php'; ?>
              <!-- Контент -->
