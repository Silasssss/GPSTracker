<?php
/* 
 *             2017-2018
 * Author : Silas riacourt <silasdu22@gmail.com>
 * 
 */

    if (isset($_GET['date']))
    {
 
        $date = $_GET['date'];

    }
    else
    {
        date_default_timezone_set('Europe/Paris');
        $date = date("Y-m-d");
    }
    require 'inc/bdd.php';
    $req = $bdd->prepare('SELECT * FROM coords WHERE DATE(date) = :date');
    $req->bindParam(':date', $_GET['date']);
    $req->execute();
    if ($donnees = $req->fetch() == 0){
            $status = "Aucune donnée pour cette période ($date)";
    }
    $req->execute();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Panel test</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <script src="inc/function.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>
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
        <li class="active"><a href="#">Home</a></li>
        <li class="dropdown">
          <a class="dropdown-toggle" data-toggle="dropdown" href="#">Page 1 <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="#">Page 1-1</a></li>
            <li><a href="#">Page 1-2</a></li>
            <li><a href="#">Page 1-3</a></li>
          </ul>
        </li>
        <li><a href="#">Page 2</a></li>
        <li><a href="#">Page 3</a></li>
      </ul>
      <ul class="nav navbar-nav navbar-right">
        <li><a href="#"><span class="glyphicon glyphicon-user"></span> Connexion</a></li>
        <li><a href="#"><span class="glyphicon glyphicon-log-in"></span> s'inscrire</a></li>
      </ul>
    </div>
  </div>
</nav>
  
<div class="container">
  <h1>Vos trajet</h1>
  <p>2 trajets en mémoire</p>
  <form class="form-horizontal">
    <fieldset>

<!-- Form Name -->
        <legend>Rechercher un trajet</legend>

<!-- Text input-->
        <div class="form-group">
            
            <label class="col-md-4 control-label" for="Date">Date</label>  
            <div class="col-md-4">
                <input id="Date" name="Date" placeholder="date" class="form-control input-md datepicker" required="" type="text">
    
            </div>
        </div>

<!-- Multiple Checkboxes -->
        <div class="form-group">
            <label class="col-md-4 control-label" for="checkboxes">Transport</label>
            <div class="col-md-4">
                <div class="checkbox">
                    <label for="checkboxes-0">
                        <input name="checkboxes" id="checkboxes-0" value="1" type="checkbox">
                            Voiture
                    </label>
                </div>
                 <div class="checkbox">
                     <label for="checkboxes-1">
                         <input name="checkboxes" id="checkboxes-1" value="2" type="checkbox">
                            Vélo
                    </label>
                </div>
            </div>
        </div>

<!-- Button -->
<div class="form-group">
  <label class="col-md-4 control-label" for="button1"></label>
  <div class="col-md-4">
    <button id="button1" name="button1" class="btn btn-primary">Rechercher</button>
  </div>
</div>
<div class="container">
    <h2>test</h2>
    <div id="map"></div>
        <script>

      function initMap() {
        var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 14,
          center: {lat: 48.1843903, lng: -2.762291},
          mapTypeId: 'terrain'
        });
        var test = [
            <?php

             while ($row = $req->fetch()){
                   $lat = $row['latitude'];
                     $lon = $row['longitude'];
                   echo 'new google.maps.LatLng('.$lat.', '.$lon.'),';
            }
             ?>
        ];
        
        var point2 = [
          {lat: 48.184873, lng: -2.7594},
          {lat: 48.1785384, lng: -2.7241418},
          {lat: 48.1698004, lng: -2.6008998},
          {lat: 48.2378594, lng: -2.4514708}         
        ];
        var flightPath = new google.maps.Polyline({
          path: test,
          geodesic: true,
          strokeColor: '#FF0000',
          strokeOpacity: 1.0,
          strokeWeight: 2
        });

        flightPath.setMap(map);
      }
    </script>
        <script>
        var infoWindow = new google.maps.InfoWindow;
          downloadUrl('http://localhost/GPSTracker/inc/convert.php', function(data) {
            var xml = data.responseXML;
            var markers = xml.documentElement.getElementsByTagName('marker');
            Array.prototype.forEach.call(markers, function(markerElem) {
              var date = markerElem.getAttribute('date');
              var capteur = markerElem.getAttribute('capteur_id');
              var point = new google.maps.LatLng(
                  parseFloat(markerElem.getAttribute('lat')),
                  parseFloat(markerElem.getAttribute('lng')));

            });
          });
        }
      doNothing();
    </script>
</div>

</fieldset>
</form>
</div>
</div>


</body>

<script>
    $('.datepicker').datepicker();
</script>
    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyANFCjBuEsUO1o49ZVkXdukdZ2OLUfnajg&callback=initMap"></script>
</html> 