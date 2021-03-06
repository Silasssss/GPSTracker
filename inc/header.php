<?php

/* 
 *             2017-2018
 * Author : Silas riacourt <silasdu22@gmail.com>
 * 
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Panel test</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <link rel="stylesheet" href="view.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <script src="inc/function.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>
    <style>

      #map {
        height: 65%;
        width: 60%;
        margin-left: auto;
        margin-right: auto;
      }
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
    </style>
</head>
<body>
<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>                        
      </button>
      <a class="navbar-brand" href="#">Panel GPS</a>
    </div>
    <div class="collapse navbar-collapse" id="myNavbar">
      <ul class="nav navbar-nav">
        <li class="active"><a href="#">Trajet </a></li>
        <li><a href="#">Carte</a></li>
      </ul>
      <ul class="nav navbar-nav navbar-right">        
        <?php if(isset($_SESSION['auth'])): ?>
            <li><a href="logout.php"><span class="glyphicon glyphicon-log-in"></span> Se déonnecter</a></li>
        <?php else: ?>
            <li><a href="login.php"><span class="glyphicon glyphicon-user"></span> Connexion</a></li>
            <li><a href="register.php"><span class="glyphicon glyphicon-log-in"></span> s'inscrire</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>
