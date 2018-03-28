<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <title>Simple Polylines</title>
    <style>
      html, body, #map-canvas {
        height: 100%;
        margin: 0px;
        padding: 0px
      }
    </style>
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp"></script>
    <script>
    function initialize() {
    var mapOptions = {
    zoom: 10,
    center: new google.maps.LatLng(48.1843903, -2.762291),
    mapTypeId: google.maps.MapTypeId.TERRAIN
    };


    var map = new google.maps.Map(document.getElementById('map-canvas'),
      mapOptions);

    var flightPlanCoordinates = [
    <?php
    require("bdd.php");
    $bdd = new PDO('mysql:host=localhost;dbname=gps;charset=utf8', 'root', '');
    $req = $bdd->prepare('SELECT latitude, longitude FROM coords WHERE 1');
    $req->execute();

    while ($row = $req->fetch()){
        $lat = $row['latitude'];
        $lon = $row['longitude'];
        echo 'new google.maps.LatLng('.$lat.', '.$lon.'),';
    }
    ?>

    ];

    var flightPath = new google.maps.Polyline({
        path: flightPlanCoordinates,
        geodesic: true,
        strokeColor: '#FF0000',
        strokeOpacity: 1.0,
        strokeWeight: 2
    });
    flightPath.setMap(map);
    }
    google.maps.event.addDomListener(window, 'load', initialize);
    </script>
        <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyANFCjBuEsUO1o49ZVkXdukdZ2OLUfnajg&callback=initMap"></script>
    </head>
    <body>
        <div id="map-canvas"></div>
    </body>
</html>