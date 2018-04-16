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
<html>
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <title>Trajet</title>
        <script src="inc/function.js"></script>
    <style>

      #map {
        height: 100%;
      }
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
    </style>
  </head>
  <body>
<h2 align="center"><?php echo $status;?></h2>
	<h3 id="dev"></h3>
    <div id="map"></div>
    <script>
          downloadUrl('http://localhost/GPSTracker/inc/convert.php', function(data) {
              
            var xml = data.responseXML;
            var polylinePlanCoordinates  = [];
            var markers = xml.documentElement.getElementsByTagName('marker');
            Array.prototype.forEach.call(markers, function(markerElem) {
              var date = markerElem.getAttribute('date');
              var capteur = markerElem.getAttribute('capteur_id');
              var point = new google.maps.LatLng(
                  parseFloat(markerElem.getAttribute('lat')),
                  parseFloat(markerElem.getAttribute('lng')));
            });
          });
      doNothing();

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
    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyANFCjBuEsUO1o49ZVkXdukdZ2OLUfnajg&callback=initMap"></script>
  </body>
</html>
