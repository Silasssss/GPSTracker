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
	<h3 id="dev"></h3>
    <div id="map"></div>
    <script>
          downloadUrl('http://localhost/GPS/inc/convert.php', function(data) {
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
      doNothing();
      function initMap() {
        var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 10,
          center: {lat: 48.1843903, lng: -2.762291},
          mapTypeId: 'terrain'
        });
		
        var point2 = [
          {lat: 48.1843903, lng: -2.762291},
          {lat: 21.291, lng: -157.821},
          {lat: -18.142, lng: 178.431},
          {lat: -27.467, lng: 153.027}
        ];
                  document.getElementById("dev").innerHTML = point2;
        var flightPath = new google.maps.Polyline({
          path: point2,
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
          downloadUrl('http://localhost/GPS/inc/convert.php', function(data) {
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
